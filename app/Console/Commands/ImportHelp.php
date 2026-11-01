<?php

namespace App\Console\Commands;

use App\Models\HelpArticle;
use App\Models\HelpCategory;
use App\Support\ManualRenderer;
use Illuminate\Console\Command;

class ImportHelp extends Command
{
    protected $signature = 'help:import {--file= : JSON written by scripts/export-manual.mjs}';

    protected $description = 'Build the help center from the ERP user manual (one category per guide, one article per chapter). Running it again overwrites edits made to those articles in the admin';

    public function handle(ManualRenderer $renderer): int
    {
        $file = $this->option('file') ?: database_path('data/manual.json');

        if (! is_file($file)) {
            $this->error("Not found: {$file}. Run: node scripts/export-manual.mjs");

            return self::FAILURE;
        }

        $guides = json_decode(file_get_contents($file), true, flags: JSON_THROW_ON_ERROR)['guides'];
        $articles = 0;

        foreach ($guides as $guide) {
            $category = HelpCategory::updateOrCreate(['slug' => $guide['id']], [
                'icon' => $guide['icon'],
                'sort' => $guide['sort'],
                'title' => $this->field($guide, 'title'),
                'summary' => $this->field($guide, 'summary'),
            ]);

            // Chapters are matched across languages by their position in the guide.
            $chapterIds = [];

            foreach ($guide['locales']['fa']['chapters'] as $position => $chapter) {
                $row = ['title' => [], 'summary' => [], 'content' => []];

                foreach ($guide['locales'] as $locale => $localized) {
                    $translated = $localized['chapters'][$position] ?? null;
                    if (! $translated) {
                        continue;
                    }
                    $row['title'][$locale] = trim($translated['number'].' '.$translated['title']);
                    $row['summary'][$locale] = $renderer->summary($translated['blocks']);
                    $row['content'][$locale] = $renderer->render($translated['blocks']);
                }

                HelpArticle::updateOrCreate(
                    ['help_category_id' => $category->id, 'slug' => $chapter['id']],
                    $row + ['sort' => $position + 1],
                );
                $chapterIds[] = $chapter['id'];
                $articles++;
            }

            // A chapter that no longer exists in the manual leaves the help center too.
            HelpArticle::where('help_category_id', $category->id)->whereNotIn('slug', $chapterIds)->delete();
        }

        $this->info(count($guides).' categories, '.$articles.' articles imported.');

        return self::SUCCESS;
    }

    /** @return array<string, string> */
    private function field(array $guide, string $key): array
    {
        return collect($guide['locales'])->map(fn ($localized) => $localized[$key])->all();
    }
}
