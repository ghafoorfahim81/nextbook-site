<?php

namespace Tests\Feature;

use App\Models\HelpArticle;
use App\Models\HelpCategory;
use App\Support\ManualRenderer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HelpImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_builds_a_category_per_guide_with_all_three_languages(): void
    {
        $this->artisan('help:import')->assertSuccessful();

        $this->assertSame(15, HelpCategory::count());
        $this->assertGreaterThan(100, HelpArticle::count());

        $sales = HelpCategory::where('slug', 'sales')->first();
        foreach (['fa', 'en', 'ps'] as $locale) {
            $this->assertNotEmpty($sales->getTranslation('title', $locale, false), "sales category has no {$locale} title");
        }

        $article = $sales->articles()->first();
        foreach (['fa', 'en', 'ps'] as $locale) {
            $this->assertNotEmpty($article->getTranslation('content', $locale, false), "first sales article has no {$locale} content");
        }
    }

    public function test_import_is_repeatable_and_does_not_duplicate(): void
    {
        $this->artisan('help:import')->assertSuccessful();
        $count = HelpArticle::count();

        $this->artisan('help:import')->assertSuccessful();

        $this->assertSame($count, HelpArticle::count());
    }

    public function test_every_imported_article_page_renders(): void
    {
        $this->artisan('help:import')->assertSuccessful();

        foreach (HelpArticle::with('category')->get() as $article) {
            $this->get("/fa/help/{$article->category->slug}/{$article->slug}")->assertOk();
        }
    }

    public function test_renderer_escapes_text_and_renders_each_block_type(): void
    {
        $html = (new ManualRenderer)->render([
            ['type' => 'p', 'text' => '<script>alert(1)</script>'],
            ['type' => 'list', 'items' => ['a', 'b']],
            ['type' => 'table', 'headers' => ['H'], 'rows' => [['cell']]],
            ['type' => 'warn', 'label' => 'Careful', 'text' => 'Do not'],
            ['type' => 'flow', 'steps' => ['one', 'two']],
            ['type' => 'figure', 'src' => '/images/user-manual/does-not-exist.png', 'caption' => 'x'],
            ['type' => 'unknown'],
        ]);

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringContainsString('<li>a</li>', $html);
        $this->assertStringContainsString('<th>H</th>', $html);
        $this->assertStringContainsString('callout-warn', $html);
        $this->assertStringContainsString('flow-step', $html);
        // A screenshot that has not been added yet is left out instead of showing a broken image.
        $this->assertStringNotContainsString('<img', $html);
    }

    public function test_a_present_screenshot_is_rendered(): void
    {
        $html = (new ManualRenderer)->render([
            ['type' => 'figure', 'src' => '/images/user-manual/sales/printing.png', 'caption' => 'Invoice print'],
        ]);

        $this->assertStringContainsString('<img src="/images/user-manual/sales/printing.png"', $html);
    }
}
