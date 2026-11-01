<?php

namespace Tests\Feature;

use App\Filament\Resources\PricingPlans\Pages\ManagePricingPlans;
use App\Models\PricingPlan;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PricingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_the_three_plans_show_their_prices_in_both_languages(): void
    {
        $this->get('/en/pricing')->assertOk()
            ->assertSeeInOrder(['Starter', 'Professional', 'Enterprise'])
            ->assertSee('$')
            ->assertSee('Up to 3 users')
            ->assertSee('Phone and WhatsApp support')
            ->assertSee('Everything in Starter, plus:')
            ->assertSee('Staff training')
            ->assertSee('Prices are in US dollars.');

        $this->get('/fa/pricing')->assertOk()
            ->assertSeeInOrder(['پایه', 'حرفه‌ای', 'سازمانی'])
            ->assertSee('۱۵۰')   // the Professional yearly price, in Persian digits
            ->assertSee('تا ۳ کاربر');
    }

    public function test_the_prices_are_the_agreed_ones(): void
    {
        $prices = PricingPlan::orderBy('sort')->get()->mapWithKeys(fn ($p) => [$p->slug => [$p->monthly, $p->yearly]])->all();

        $this->assertSame(['starter' => [10, 100], 'professional' => [18, 150], 'enterprise' => [25, 220]], $prices);
    }

    public function test_the_yearly_saving_is_worked_out_from_the_prices_not_typed_in(): void
    {
        $starter = PricingPlan::where('slug', 'starter')->first();

        $this->assertSame(20, $starter->yearlySaving());          // 12 x 10 - 100
        $this->assertSame(17, $starter->yearlySavingPercent());
        $this->assertSame(8.3, $starter->yearlyPerMonth());       // 100 / 12

        $starter->update(['yearly' => 150]);                      // yearly dearer than twelve months: no saving, never negative
        $this->assertSame(0, $starter->fresh()->yearlySaving());
    }

    public function test_features_that_are_not_built_are_labelled_coming_soon(): void
    {
        $enterprise = PricingPlan::where('slug', 'enterprise')->first();
        $soon = collect($enterprise->items())->where('soon', true)->pluck('text')->all();

        $this->assertEqualsCanonicalizing(['Fixed assets', 'Projects', 'Budgeting', 'Consolidated reporting', 'Donor-ready reporting (for NGOs)'],
            collect($enterprise->getTranslation('points', 'en'))->filter(fn ($p) => is_array($p) && $p['soon'])->pluck('text')->all());
        $this->assertNotEmpty($soon);

        $this->get('/en/pricing')->assertSee('Coming soon')->assertSee('Items marked');
        // The built things are not labelled.
        $this->assertFalse(collect($enterprise->items())->firstWhere('text', 'Unlimited users')['soon']);
    }

    public function test_a_plan_without_a_fixed_price_shows_its_text_instead(): void
    {
        PricingPlan::where('slug', 'enterprise')->update(['monthly' => null, 'yearly' => null, 'price_label' => ['fa' => 'تماس بگیرید', 'en' => 'Contact us']]);

        $this->get('/en/pricing')->assertOk()->assertSee('Contact us');
    }

    public function test_choosing_a_plan_opens_the_demo_form_with_the_plan_mentioned(): void
    {
        $this->get('/en/demo?plan=professional')->assertOk()->assertSee('I am interested in the Professional plan.');
        $this->get('/fa/demo?plan=starter')->assertOk()->assertSee('پکیج «پایه» را می‌خواهم.');
        // An unknown plan changes nothing.
        $this->get('/en/demo?plan=made-up')->assertOk()->assertDontSee('I am interested');
    }

    public function test_unpublished_plans_and_addons_are_hidden(): void
    {
        PricingPlan::where('slug', 'starter')->update(['is_published' => false]);

        $this->get('/en/pricing')->assertDontSee('For small businesses, shops and stores')->assertSee('Professional');
    }

    public function test_the_team_can_edit_a_plan_and_its_included_items_keep_their_shape(): void
    {
        $this->actingAs(User::factory()->create());
        $plan = PricingPlan::where('slug', 'enterprise')->first();

        Livewire::test(ManagePricingPlans::class)
            ->mountAction(TestAction::make('edit')->table($plan))
            ->fillForm(['monthly' => 30, 'yearly' => 280])
            ->callMountedAction()
            ->assertHasNoFormErrors();

        $plan->refresh();
        app()->setLocale('en');
        $this->assertSame([30, 280], [$plan->monthly, $plan->yearly]);
        // The items, including which are "coming soon", survived the round trip through the form.
        $this->assertTrue(collect($plan->items())->firstWhere('text', 'Projects')['soon']);
        $this->assertFalse(collect($plan->items())->firstWhere('text', 'Unlimited users')['soon']);
    }
}
