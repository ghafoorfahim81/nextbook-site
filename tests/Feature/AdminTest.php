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
                'title' => ['fa' => 'فروش', 'en' => 'Sales', 'ps' => null],
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
