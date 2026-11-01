<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\DemoRequest;
use App\Models\Feature;
use App\Models\HelpArticle;
use App\Models\HelpCategory;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_every_public_page_renders_in_both_languages(): void
    {
        foreach (['fa', 'en'] as $locale) {
            foreach (['', '/features', '/features/inventory', '/industries', '/industries/pharmacy', '/pricing', '/faq',
                '/about', '/security', '/help', '/contact', '/demo'] as $path) {
                $this->get("/{$locale}{$path}")->assertOk();
            }
        }
    }

    public function test_pages_use_the_right_direction_and_language(): void
    {
        $this->get('/fa')->assertSee('dir="rtl"', false)->assertSee('lang="fa"', false);
        $this->get('/en')->assertSee('dir="ltr"', false)->assertSee('lang="en"', false);
    }

    public function test_root_redirects_to_the_visitors_language_and_defaults_to_dari(): void
    {
        $this->withHeader('Accept-Language', 'fa-AF,fa;q=0.9')->get('/')->assertRedirect('/fa');
        $this->withHeader('Accept-Language', 'en-US,en;q=0.9')->get('/')->assertRedirect('/en');
        // A language we do not offer falls back to Dari.
        $this->withHeader('Accept-Language', 'de-DE')->get('/')->assertRedirect('/fa');
    }

    public function test_seeded_lists_are_real_lists_in_each_language(): void
    {
        // Guards against the translation columns being stored as JSON inside JSON.
        $feature = Feature::where('slug', 'sales')->first();
        $this->assertIsArray($feature->getTranslation('points', 'fa'));
        $this->assertIsArray($feature->getTranslation('points', 'en'));

        $this->get('/en/features/sales')->assertSee('Quotations, sales orders and sales invoices');
        $this->get('/fa/industries/pharmacy')->assertSee('پیگیری بچ و تاریخ انقضا برای هر جنس');
    }

    public function test_unknown_pages_and_locales_are_404(): void
    {
        $this->get('/fa/nope')->assertNotFound();
        $this->get('/xx')->assertNotFound();
        $this->get('/fa/features/does-not-exist')->assertNotFound();
    }

    public function test_unpublished_content_is_hidden(): void
    {
        Feature::where('slug', 'inventory')->update(['is_published' => false]);

        $this->get('/fa/features/inventory')->assertNotFound();
        $this->get('/fa/features')->assertDontSee(route('features.show', ['locale' => 'fa', 'feature' => 'inventory']));
    }

    public function test_coming_soon_features_are_labelled_and_not_sold_as_available(): void
    {
        $this->get('/en/features/pos')->assertOk()->assertSee('Coming soon');
        // The home page lists only features that exist.
        $this->get('/en')->assertDontSee('Point of sale');
    }

    public function test_english_falls_back_to_dari_when_a_translation_is_missing(): void
    {
        $feature = Feature::where('slug', 'languages')->first();
        $feature->forgetTranslation('title', 'en')->save();

        $this->get('/en/features')->assertOk()->assertSee($feature->getTranslation('title', 'fa'));
    }

    public function test_demo_request_is_stored(): void
    {
        $this->post('/fa/demo', [
            'name' => 'Ahmad', 'phone' => '0700000000', 'business_type' => 'pharmacy', 'branches' => 2,
        ])->assertRedirect();

        $this->assertDatabaseHas('demo_requests', ['name' => 'Ahmad', 'business_type' => 'pharmacy', 'locale' => 'fa', 'status' => 'new']);
        $this->assertSame(1, DemoRequest::count());
    }

    public function test_demo_request_needs_a_name_and_phone_and_a_known_business_type(): void
    {
        $this->post('/fa/demo', ['name' => '', 'phone' => '', 'business_type' => 'spaceship'])
            ->assertSessionHasErrors(['name', 'phone', 'business_type']);

        $this->assertSame(0, DemoRequest::count());
    }

    public function test_honeypot_drops_bot_submissions_silently(): void
    {
        $this->post('/en/demo', ['name' => 'Bot', 'phone' => '1', 'website' => 'http://spam'])->assertSessionHas('sent');
        $this->post('/en/contact', ['name' => 'Bot', 'message' => 'x', 'phone' => '1', 'website' => 'http://spam'])->assertSessionHas('sent');

        $this->assertSame(0, DemoRequest::count());
        $this->assertSame(0, ContactMessage::count());
    }

    public function test_contact_message_needs_a_way_to_reply(): void
    {
        $this->post('/fa/contact', ['name' => 'Ali', 'message' => 'Hello'])->assertSessionHasErrors('phone');
        $this->post('/fa/contact', ['name' => 'Ali', 'message' => 'Hello', 'email' => 'ali@example.com'])->assertSessionDoesntHaveErrors();

        $this->assertSame(1, ContactMessage::count());
    }

    public function test_help_search_and_feedback(): void
    {
        $category = HelpCategory::create([
            'slug' => 'sales', 'icon' => 'shopping-cart',
            'title' => ['fa' => 'فروش', 'en' => 'Sales'],
        ]);
        $article = HelpArticle::create([
            'help_category_id' => $category->id, 'slug' => 'first-invoice',
            'title' => ['fa' => 'اولین بیل', 'en' => 'Your first invoice'],
            'summary' => ['en' => 'Record a sale'],
            'content' => ['en' => '<p>Open Sales</p>'],
        ]);

        $this->get('/en/help/sales/first-invoice')->assertOk()->assertSee('Open Sales', false);
        $this->get('/fa/help/sales/first-invoice')->assertOk()->assertSee(__('site.help.not_translated', [], 'fa'));

        $this->get('/en/help/search?q=invoice')->assertOk()->assertSee('Your first invoice');
        $this->get('/en/help/search?q=nothing-matches')->assertOk()->assertSee(__('site.help.no_results', [], 'en'));

        $this->post("/en/help/article/{$article->id}/feedback", ['helpful' => 'yes']);
        $this->assertSame(1, $article->fresh()->helpful_yes);
    }

    public function test_sitemap_lists_every_language(): void
    {
        $this->get('/sitemap.xml')->assertOk()
            ->assertSee('/fa/features/inventory', false)
            ->assertSee('/en/features/inventory', false);
    }
}
