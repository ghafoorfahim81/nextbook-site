<?php

namespace App\Support;

use App\Models\Faq;
use App\Models\Feature;
use App\Models\HelpArticle;
use App\Models\Industry;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;

/**
 * Searches everything a visitor can read on the site: features, industries, help articles,
 * blog posts and FAQ answers. Text is matched in the visitor's language, falling back to
 * Dari where a translation is missing, which is what the pages themselves show.
 */
class SiteSearch
{
    public const MIN_LENGTH = 2;

    /** Makes what was typed comparable with what is stored (Arabic keyboards type ي and ك). */
    public static function normalize(string $query): string
    {
        $query = str_replace(['ي', 'ك', 'ى', 'ە', 'ۀ'], ['ی', 'ک', 'ی', 'ه', 'ه'], $query);

        return mb_strtolower(trim(preg_replace('/\s+/u', ' ', $query)));
    }

    /**
     * @return array<int, array{key: string, label: string, items: array<int, array{title: string, snippet: string, url: string, meta: string}>}>
     */
    public function search(string $query, int $perGroup = 5): array
    {
        $query = self::normalize($query);

        if (mb_strlen($query) < self::MIN_LENGTH) {
            return [];
        }

        $groups = [
            ['features', fn () => $this->features($query, $perGroup)],
            ['industries', fn () => $this->industries($query, $perGroup)],
            ['help', fn () => $this->help($query, $perGroup)],
            ['blog', fn () => $this->blog($query, $perGroup)],
            ['faq', fn () => $this->faq($query, $perGroup)],
        ];

        $result = [];

        foreach ($groups as [$key, $run]) {
            $items = $run();

            if ($items) {
                $result[] = ['key' => $key, 'label' => __('site.search.groups.'.$key), 'items' => $items];
            }
        }

        return $result;
    }

    private function features(string $q, int $limit): array
    {
        return $this->query(Feature::published(), ['title', 'summary', 'overview', 'highlights'], $q, $limit)
            ->map(fn (Feature $f) => $this->item($f->title, $f->summary, $q, [$f->overview, $f->summary], route('features.show', $f)))
            ->all();
    }

    private function industries(string $q, int $limit): array
    {
        return $this->query(Industry::published(), ['title', 'summary', 'overview', 'highlights'], $q, $limit)
            ->map(fn (Industry $i) => $this->item($i->title, $i->summary, $q, [$i->overview, $i->summary], route('industries.show', $i)))
            ->all();
    }

    private function help(string $q, int $limit): array
    {
        return $this->query(HelpArticle::published()->with('category'), ['title', 'summary', 'content'], $q, $limit)
            ->map(fn (HelpArticle $a) => $this->item(
                $a->title, $a->summary, $q, [$a->content, $a->summary], route('help.article', [$a->category, $a]), $a->category->title,
            ))
            ->all();
    }

    private function blog(string $q, int $limit): array
    {
        return $this->query(Post::live(), ['title', 'excerpt', 'content'], $q, $limit)
            ->map(fn (Post $p) => $this->item($p->title, $p->excerpt, $q, [$p->content, $p->excerpt], route('blog.show', $p)))
            ->all();
    }

    private function faq(string $q, int $limit): array
    {
        return $this->query(Faq::published(), ['question', 'answer'], $q, $limit)
            ->map(fn (Faq $f) => $this->item($f->question, $f->answer, $q, [$f->answer], route('faq').'#faq-'.$f->id))
            ->all();
    }

    /**
     * Rows whose text contains the query in any of the columns, those whose first column
     * (the title) matches before the rest.
     *
     * @param  list<string>  $columns
     */
    private function query(Builder $builder, array $columns, string $q, int $limit)
    {
        $locale = app()->getLocale();
        $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $q).'%';

        // The visitor's language where it exists, otherwise Dari.
        $text = fn (string $column) => "lower(coalesce(nullif({$column}->>?, ''), {$column}->>'fa'))";

        $builder->where(function (Builder $where) use ($columns, $text, $locale, $like) {
            foreach ($columns as $column) {
                $where->orWhereRaw($text($column).' like ?', [$locale, $like]);
            }
        });

        return $builder
            ->orderByRaw('('.$text($columns[0]).' like ?) desc', [$locale, $like])
            ->limit($limit)
            ->get();
    }

    /** @param list<?string> $sources text to take the snippet from, best first */
    private function item(string $title, ?string $summary, string $q, array $sources, string $url, string $meta = ''): array
    {
        return [
            'title' => $title,
            'snippet' => $this->snippet($sources, $q) ?: (string) $summary,
            'url' => $url,
            'meta' => $meta,
        ];
    }

    /** A short stretch of plain text around the first place the query appears. */
    private function snippet(array $sources, string $q, int $length = 140): string
    {
        foreach ($sources as $source) {
            $text = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $source))));
            $position = $text === '' ? false : mb_stripos($text, $q);

            if ($position === false) {
                continue;
            }

            $start = max(0, $position - (int) ($length / 3));
            $piece = mb_substr($text, $start, $length);

            return ($start > 0 ? '… ' : '').trim($piece).(mb_strlen($text) > $start + $length ? ' …' : '');
        }

        return '';
    }
}
