<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\Post;
use App\Support\SiteSearch;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
        $this->artisan('help:import');
    }

    private function search(string $locale, string $q): array
    {
        return $this->getJson("/{$locale}/search?q=".urlencode($q))->assertOk()->json('groups');
    }

    private function keys(array $groups): array
    {
        return array_column($groups, 'key');
    }

    public function test_it_finds_results_across_the_site_in_the_visitors_language(): void
    {
        $groups = $this->search('en', 'invoice');

        $this->assertContains('features', $this->keys($groups));
        $this->assertContains('help', $this->keys($groups));
        $this->assertContains('blog', $this->keys($groups));

        $features = collect($groups)->firstWhere('key', 'features');
        $this->assertSame('Sales', $features['items'][0]['title']);
        $this->assertStringContainsString('/en/features/sales', $features['items'][0]['url']);
    }

    public function test_it_finds_dari_text_and_accepts_arabic_keyboard_letters(): void
    {
        $persian = $this->search('fa', 'فاکتور');
        $this->assertContains('features', $this->keys($persian));

        // "ك" and "ي" typed on an Arabic keyboard match the Persian "ک" and "ی" in the content.
        $this->assertSame('فاکتور', SiteSearch::normalize('فاكتور'));
        $this->assertSame(
            $this->keys($persian),
            $this->keys($this->search('fa', 'فاكتور')),
        );
    }

    public function test_a_title_match_comes_before_a_text_match(): void
    {
        $features = collect($this->search('en', 'sales'))->firstWhere('key', 'features');

        $this->assertSame('Sales', $features['items'][0]['title']);
    }

    public function test_short_or_empty_queries_return_nothing(): void
    {
        $this->assertSame([], $this->search('en', ''));
        $this->assertSame([], $this->search('en', 'a'));
    }

    public function test_wildcard_characters_are_matched_literally(): void
    {
        $this->assertSame([], $this->search('en', '%%'));
        $this->assertSame([], $this->search('en', '__'));
    }

    public function test_it_falls_back_to_dari_where_a_translation_is_missing(): void
    {
        Feature::where('slug', 'languages')->first()->forgetTranslation('title', 'en')->save();

        $titles = collect($this->search('en', 'تقویم'))->firstWhere('key', 'features')['items'] ?? [];

        $this->assertNotEmpty($titles);
    }

    public function test_unpublished_and_scheduled_content_is_not_searchable(): void
    {
        Post::where('slug', 'draft-post-reverse')->update(['is_published' => false]);
        Feature::where('slug', 'sales')->update(['is_published' => false]);

        $groups = $this->search('en', 'draft, post and reverse');
        $this->assertNotContains('blog', $this->keys($groups));

        $features = collect($this->search('en', 'sales'))->firstWhere('key', 'features');
        $titles = array_column($features['items'] ?? [], 'title');
        $this->assertNotContains('Sales', $titles);
    }

    public function test_the_results_page_works_without_javascript(): void
    {
        $this->get('/en/search?q=payroll')->assertOk()->assertSee('Results for')->assertSee('HR and payroll');
        $this->get('/fa/search')->assertOk()->assertSee(__('site.search.hint', [], 'fa'));
        $this->get('/en/search?q=zzzzqq')->assertOk()->assertSee('Nothing found for');
    }

    public function test_the_header_has_the_search_box_and_the_theme_switch(): void
    {
        $page = $this->get('/en')->assertOk();

        $page->assertSee('id="search-dialog"', false);
        $page->assertSee('data-search-open', false);
        $page->assertSee('data-theme-toggle', false);
        // The saved theme is applied before the stylesheet, so a dark page never flashes white.
        $page->assertSee("localStorage.getItem('theme')", false);
        $page->assertSee('name="color-scheme"', false);
    }
}
