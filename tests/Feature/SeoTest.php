<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    /** Every JSON-LD block on the page, decoded (a block that is not valid JSON fails the test). */
    private function jsonLd(string $url): array
    {
        $html = $this->get($url)->assertOk()->getContent();
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

        return array_map(function (string $json) {
            $decoded = json_decode($json, true);
            $this->assertNotNull($decoded, 'invalid JSON-LD: '.json_last_error_msg());

            return $decoded;
        }, $matches[1]);
    }

    private function ofType(array $blocks, string $type): ?array
    {
        return collect($blocks)->firstWhere('@type', $type);
    }

    public function test_robots_txt_hides_the_admin_and_points_to_the_sitemap(): void
    {
        $body = $this->get('/robots.txt')->assertOk()->assertHeader('Content-Type', 'text/plain; charset=UTF-8')->getContent();

        $this->assertStringContainsString('Disallow: /admin', $body);
        $this->assertStringContainsString('Disallow: /*/search', $body);
        $this->assertStringContainsString('Sitemap: '.url('/sitemap.xml'), $body);
    }

    public function test_shared_links_carry_a_title_a_description_and_a_preview_image(): void
    {
        $page = $this->get('/en/features/sales')->assertOk();

        $page->assertSee('<meta property="og:title" content="Sales · NextBook">', false);
        $page->assertSee('<meta property="og:url" content="'.url('/en/features/sales').'">', false);
        $page->assertSee('<meta property="og:locale" content="en_US">', false);
        $page->assertSee('<meta property="og:locale:alternate" content="fa_AF">', false);
        $page->assertSee('<meta property="og:image" content="'.asset('images/og.png').'">', false);
        $page->assertSee('name="twitter:card" content="summary_large_image"', false);

        $this->get('/fa')->assertSee('og:locale" content="fa_AF"', false);
    }

    public function test_the_preview_image_and_icons_exist(): void
    {
        foreach (['images/og.png', 'favicon.ico', 'favicon-32.png', 'icon-192.png', 'icon-512.png', 'apple-touch-icon.png'] as $file) {
            $this->assertFileExists(public_path($file));
        }

        [$width, $height] = getimagesize(public_path('images/og.png'));
        $this->assertSame([1200, 630], [$width, $height]);
    }

    public function test_every_page_names_the_organisation(): void
    {
        $org = $this->ofType($this->jsonLd('/en'), 'Organization');

        $this->assertSame('NextBook', $org['name']);
        $this->assertSame('info@nextbook.af', $org['email']);
        $this->assertSame('+93728507537', $org['contactPoint'][0]['telephone']);
    }

    public function test_the_faq_is_marked_up_for_rich_results(): void
    {
        $faq = $this->ofType($this->jsonLd('/en/faq'), 'FAQPage');

        $this->assertCount(8, $faq['mainEntity']);
        $this->assertSame('Question', $faq['mainEntity'][0]['@type']);
        $this->assertNotEmpty($faq['mainEntity'][0]['acceptedAnswer']['text']);
    }

    public function test_the_pricing_page_lists_every_plan_as_an_offer_in_dollars(): void
    {
        $app = $this->ofType($this->jsonLd('/en/pricing'), 'SoftwareApplication');
        $prices = array_column($app['offers'], 'price');

        $this->assertSame([10, 100, 18, 150, 25, 220], $prices);
        $this->assertSame(['USD'], array_unique(array_column($app['offers'], 'priceCurrency')));
    }

    public function test_a_blog_post_is_marked_up_as_an_article_with_its_date(): void
    {
        $article = $this->ofType($this->jsonLd('/en/blog/draft-post-reverse'), 'Article');

        $this->assertStringContainsString('Draft, post and reverse', $article['headline']);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T/', $article['datePublished']);
        $this->assertSame('en', $article['inLanguage']);
        $this->get('/en/blog/draft-post-reverse')->assertSee('og:type" content="article"', false);
    }

    public function test_text_in_the_markup_cannot_close_the_script_tag(): void
    {
        \App\Models\Faq::first()->setTranslation('answer', 'en', 'Bad </script><script>alert(1)</script>')->save();

        $html = $this->get('/en/faq')->getContent();

        $this->assertStringNotContainsString('</script><script>alert(1)', $html);
        $this->jsonLd('/en/faq'); // and the block is still valid JSON
    }

    public function test_pages_that_are_no_use_in_a_search_engine_say_so(): void
    {
        $this->get('/en/search?q=invoice')->assertSee('<meta name="robots" content="noindex, follow">', false);
        $this->get('/en/login')->assertSee('<meta name="robots" content="noindex, follow">', false);
        $this->get('/en/features')->assertDontSee('name="robots"', false);
    }

    public function test_statistics_are_off_unless_configured(): void
    {
        $this->get('/en')->assertDontSee('data-domain', false);

        config(['site.analytics.src' => 'https://plausible.io/js/script.js', 'site.analytics.domain' => 'nextbook.af']);

        $this->get('/en')->assertSee('data-domain="nextbook.af" src="https://plausible.io/js/script.js"', false);
    }
}
