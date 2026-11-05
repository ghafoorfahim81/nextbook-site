<?php

namespace Tests\Feature;

use App\Filament\Resources\ContactMessages\Pages\ManageContactMessages;
use App\Filament\Resources\DemoRequests\Pages\ManageDemoRequests;
use App\Filament\Resources\Faqs\Pages\ManageFaqs;
use App\Filament\Resources\Features\Pages\ManageFeatures;
use App\Filament\Resources\HelpArticles\Pages\ManageHelpArticles;
use App\Filament\Resources\HelpCategories\Pages\ManageHelpCategories;
use App\Filament\Resources\Industries\Pages\ManageIndustries;
use App\Filament\Resources\Posts\Pages\ManagePosts;
use App\Filament\Resources\PricingPlans\Pages\ManagePricingPlans;
use App\Models\DemoRequest;
use App\Models\Feature;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_guests_are_sent_to_the_login_page(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_the_profile_page_offers_the_authenticator_app(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/profile')->assertOk();

        // The authenticator app is registered as an option, and is not forced on anyone.
        $panel = \Filament\Facades\Filament::getPanel('admin');
        $this->assertArrayHasKey('app', $panel->getMultiFactorAuthenticationProviders());
        $this->assertFalse($panel->isMultiFactorAuthenticationRequired());
    }

    public function test_the_authenticator_secret_and_recovery_codes_are_stored_encrypted(): void
    {
        $user = User::factory()->create();

        $user->saveAppAuthenticationSecret('JBSWY3DPEHPK3PXP');
        $user->saveAppAuthenticationRecoveryCodes(['aaaa-bbbb', 'cccc-dddd']);

        $raw = \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->first();
        $this->assertStringNotContainsString('JBSWY3DPEHPK3PXP', $raw->app_authentication_secret);
        $this->assertStringNotContainsString('aaaa-bbbb', $raw->app_authentication_recovery_codes);

        $fresh = User::find($user->id);
        $this->assertSame('JBSWY3DPEHPK3PXP', $fresh->getAppAuthenticationSecret());
        $this->assertSame(['aaaa-bbbb', 'cccc-dddd'], $fresh->getAppAuthenticationRecoveryCodes());
        $this->assertSame($user->email, $fresh->getAppAuthenticationHolderName());

        // Neither is ever included when the user is turned into an array or JSON.
        $this->assertArrayNotHasKey('app_authentication_secret', $fresh->toArray());
    }

    public function test_the_team_can_open_every_content_page(): void
    {
        $this->actingAs(User::factory()->create());

        foreach ([
            ManageFeatures::class, ManageIndustries::class, ManagePricingPlans::class, ManageFaqs::class,
            ManageHelpCategories::class, ManageHelpArticles::class, ManageDemoRequests::class, ManageContactMessages::class, ManagePosts::class,
        ] as $page) {
            Livewire::test($page)->assertSuccessful();
        }
    }

    public function test_editing_a_feature_shows_and_saves_every_language(): void
    {
        $this->actingAs(User::factory()->create());
        $feature = Feature::where('slug', 'sales')->first();

        Livewire::test(ManageFeatures::class)
            ->mountAction(TestAction::make('edit')->table($feature))
            ->assertSchemaStateSet([
                'title' => ['fa' => 'فروش', 'en' => 'Sales', 'ps' => 'پلور'],
            ])
            ->fillForm(['title.en' => 'Selling'])
            ->callMountedAction()
            ->assertHasNoFormErrors();

        $feature->refresh();
        $this->assertSame('Selling', $feature->getTranslation('title', 'en'));
        // The other language is untouched.
        $this->assertSame('فروش', $feature->getTranslation('title', 'fa'));
    }

    public function test_a_demo_request_can_be_followed_up(): void
    {
        $this->actingAs(User::factory()->create());
        $request = DemoRequest::create(['name' => 'Ahmad', 'phone' => '0700000000']);

        Livewire::test(ManageDemoRequests::class)
            ->mountAction(TestAction::make('edit')->table($request))
            ->fillForm(['status' => 'contacted', 'notes' => 'Called, demo on Saturday'])
            ->callMountedAction()
            ->assertHasNoFormErrors();

        $this->assertSame('contacted', $request->fresh()->status);
    }
}
