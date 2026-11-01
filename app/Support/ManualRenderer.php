<?php

namespace App\Support;

/**
 * Turns the ERP user manual's content blocks (paragraphs, tables, notes, step flows,
 * figures ...) into the HTML a help article stores.
 */
class ManualRenderer
{
    /** @param array<int, array<string, mixed>> $blocks */
    public function render(array $blocks): string
    {
        return implode("\n", array_filter(array_map(fn (array $block) => $this->block($block), $blocks)));
    }

    /** First paragraph, shortened: the article's summary on the listing pages. */
    public function summary(array $blocks, int $limit = 170): string
    {
        foreach ($blocks as $block) {
            if (($block['type'] ?? null) === 'p' && filled($block['text'] ?? null)) {
                return mb_strlen($block['text']) > $limit
                    ? rtrim(mb_substr($block['text'], 0, $limit - 1)).'…'
                    : $block['text'];
            }
        }

        return '';
    }

    private function block(array $block): ?string
    {
        $text = e($block['text'] ?? '');

        return match ($block['type'] ?? null) {
            'p' => "<p>{$text}</p>",
            'h3' => "<h3>{$text}</h3>",
            'h4' => "<h4>{$text}</h4>",
            'list' => $this->list('ul', $block['items'] ?? []),
            'ol' => $this->list('ol', $block['items'] ?? []),
            'table' => $this->table($block['headers'] ?? [], $block['rows'] ?? []),
            'note', 'tip', 'warn' => $this->callout($block['type'], $block['label'] ?? '', $block['text'] ?? ''),
            'flow' => $this->flow($block['steps'] ?? []),
            'formula' => "<div class=\"formula\">{$text}</div>",
            'figure' => $this->figure($block),
            default => null,
        };
    }

    private function list(string $tag, array $items): string
    {
        $li = implode('', array_map(fn ($item) => '<li>'.e($item).'</li>', $items));

        return "<{$tag}>{$li}</{$tag}>";
    }

    private function table(array $headers, array $rows): string
    {
        $head = implode('', array_map(fn ($h) => '<th>'.e($h).'</th>', $headers));
        $body = implode('', array_map(
            fn (array $row) => '<tr>'.implode('', array_map(fn ($cell) => '<td>'.e($cell).'</td>', $row)).'</tr>',
            $rows,
        ));

        return "<div class=\"table-wrap\"><table><thead><tr>{$head}</tr></thead><tbody>{$body}</tbody></table></div>";
    }

    private function callout(string $type, string $label, string $text): string
    {
        $label = $label !== '' ? '<strong>'.e($label).'</strong> ' : '';

        return "<div class=\"callout callout-{$type}\">{$label}".e($text).'</div>';
    }

    private function flow(array $steps): string
    {
        $items = implode('<span class="flow-arrow" aria-hidden="true">→</span>', array_map(fn ($s) => '<span class="flow-step">'.e($s).'</span>', $steps));

        return "<div class=\"flow\">{$items}</div>";
    }

    /** A screenshot is shown only when its file has been added; otherwise nothing is rendered. */
    private function figure(array $block): ?string
    {
        $src = $block['src'] ?? '';

        if ($src === '' || ! is_file(public_path(ltrim($src, '/')))) {
            return null;
        }

        $caption = e($block['caption'] ?? '');

        return '<figure><img src="'.e($src).'" alt="'.$caption.'" loading="lazy">'
            .($caption !== '' ? "<figcaption>{$caption}</figcaption>" : '').'</figure>';
    }
}
