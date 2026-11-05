<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\DemoRequest;
use App\Models\Feature;
use App\Models\HelpArticle;
use App\Models\HelpCategory;
use App\Models\Industry;
use App\Models\Post;
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
                '/about', '/security', '/help', '/contact', '/demo', '/login', '/terms', '/privacy', '/blog', '/blog/draft-post-reverse'] as $path) {
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

    public function test_detail_pages_carry_real_content_not_just_a_summary(): void
    {
        $this->get('/fa/features/sales')
            ->assertSee('فروش سریع با بارکد')   // a highlight
            ->assertSee('در یک روز کاری')        // the walk through a day
            ->assertSee(route('help.category', ['locale' => 'fa', 'category' => 'sales']), false);

        $this->get('/en/industries/pharmacy')
            ->assertSee('Near expiry')
            ->assertSee('A day with NextBook')
            ->assertSee(route('features.show', ['locale' => 'en', 'feature' => 'inventory']), false);
    }

    public function test_every_feature_and_industry_has_an_overview_highlights_and_steps_in_both_languages(): void
    {
        foreach (Feature::where('status', 'available')->get() as $feature) {
            foreach (['fa', 'en'] as $locale) {
                $this->assertNotEmpty($feature->getTranslation('overview', $locale, false), "{$feature->slug} overview {$locale}");
                $this->assertGreaterThanOrEqual(4, count($feature->getTranslation('highlights', $locale, false)), "{$feature->slug} highlights {$locale}");
                $this->assertNotEmpty($feature->getTranslation('steps', $locale, false), "{$feature->slug} steps {$locale}");
            }
        }

        $industries = Industry::all();
        $this->assertCount(17, $industries);

        foreach ($industries as $industry) {
            foreach (['fa', 'en'] as $locale) {
                $this->assertNotEmpty($industry->getTranslation('overview', $locale, false), "{$industry->slug} overview {$locale}");
                $this->assertCount(4, $industry->getTranslation('highlights', $locale, false), "{$industry->slug} highlights {$locale}");
                $this->assertNotEmpty($industry->getTranslation('steps', $locale, false), "{$industry->slug} steps {$locale}");
            }
            // Every module a trade points to has to exist, or its card would silently vanish.
            foreach ($industry->feature_slugs as $slug) {
                $this->assertTrue(Feature::where('slug', $slug)->exists(), "{$industry->slug} points to missing feature {$slug}");
            }
        }
    }

    public function test_no_text_carries_stray_escape_characters(): void
    {
        foreach ([...Feature::all(), ...Industry::all()] as $row) {
            foreach (['overview', 'highlights', 'steps', 'points'] as $field) {
                // Look at the text itself: JSON-encoding would turn every real line break into "\n".
                $translations = $row->getTranslations($field);
                array_walk_recursive($translations, function ($text) use ($row, $field) {
                    $this->assertStringNotContainsString('\\', (string) $text, "{$row->slug}.{$field}");
                });
            }
        }
    }

    public function test_the_home_page_shows_the_product_in_the_visitors_language(): void
    {
        $this->get('/fa')->assertSee('images/screenshots/dashboard-fa.webp', false);
        $this->get('/en')->assertSee('images/screenshots/dashboard-en.webp', false);
    }

    public function test_the_blog_lists_live_posts_and_hides_drafts_and_scheduled_ones(): void
    {
        $this->get('/en/blog')->assertOk()
            ->assertSee('Draft, post and reverse')
            ->assertSee('min read');

        Post::where('slug', 'draft-post-reverse')->update(['is_published' => false]);
        $this->get('/en/blog/draft-post-reverse')->assertNotFound();

        Post::where('slug', 'draft-post-reverse')->update(['is_published' => true, 'published_at' => now()->addDay()]);
        $this->get('/en/blog/draft-post-reverse')->assertNotFound();
        $this->get('/en/blog')->assertDontSee('Draft, post and reverse');
    }

    public function test_blog_dates_follow_the_visitors_calendar(): void
    {
        Post::where('slug', 'first-week-with-nextbook')->update(['published_at' => '2026-11-01 09:00:00']);

        // 10 Aban 1405 in Persian digits on the Dari page, and the Gregorian date on the English one.
        $this->get('/fa/blog/first-week-with-nextbook')->assertSee('۱۰ عقرب ۱۴۰۵');
        $this->get('/en/blog/first-week-with-nextbook')->assertSee('1 November 2026');
    }

    public function test_blog_posts_are_in_the_sitemap(): void
    {
        $this->get('/sitemap.xml')->assertSee('/fa/blog/draft-post-reverse', false)->assertSee('/en/blog/fifo-and-landed-costs', false);
    }

    public function test_sign_in_sends_the_visitor_to_their_companys_subdomain(): void
    {
        foreach (['noor', 'Noor', ' noor ', 'noor.nextbook.af', 'https://noor.nextbook.af/login', 'noor-trading'] as $input) {
            $expected = str_contains($input, '-') ? 'noor-trading' : 'noor';

            $this->post('/fa/login', ['workspace' => $input])->assertRedirect("https://{$expected}.nextbook.af/login");
        }
    }

    public function test_sign_in_never_leaves_our_own_domain(): void
    {
        foreach (['evil.com', 'a.b', '../x', 'noor@evil.com', 'https://evil.com/noor', '-bad', 'bad-', '', 'x y', str_repeat('a', 64)] as $input) {
            $this->from('/fa/login')->post('/fa/login', ['workspace' => $input])
                ->assertRedirect('/fa/login')->assertSessionHasErrors('workspace');
        }
    }

    public function test_terms_and_privacy_come_from_the_erp_in_the_visitors_language(): void
    {
        $this->get('/en/terms')->assertOk()->assertSee('Terms of Service')->assertDontSee(__('site.legal.not_translated', [], 'en'));
        $this->get('/fa/terms')->assertOk()->assertSee('شرایط استفاده از خدمات')->assertDontSee(__('site.legal.not_translated', [], 'fa'));
        $this->get('/en/privacy')->assertOk()->assertSee('Privacy');
    }

    public function test_the_footer_links_to_the_legal_pages_and_there_is_no_self_service_trial(): void
    {
        $this->get('/en')->assertSee('/en/terms', false)->assertSee('/en/privacy', false)->assertDontSee('/register', false);
    }

    public function test_sitemap_lists_every_language(): void
    {
        $this->get('/sitemap.xml')->assertOk()
            ->assertSee('/fa/features/inventory', false)
            ->assertSee('/en/features/inventory', false);
    }
}
