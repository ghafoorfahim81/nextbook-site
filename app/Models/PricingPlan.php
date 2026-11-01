<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PricingPlan extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public array $translatable = ['name', 'audience', 'description', 'includes', 'price_label', 'period', 'points'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'monthly' => 'integer',
            'yearly' => 'integer',
        ];
    }

    public function hasFixedPrice(): bool
    {
        return $this->monthly !== null && $this->yearly !== null;
    }

    /** What paying yearly saves against twelve monthly payments, in dollars (never negative). */
    public function yearlySaving(): int
    {
        return $this->hasFixedPrice() ? max(0, $this->monthly * 12 - $this->yearly) : 0;
    }

    /** The saving as a whole percentage of the twelve-month price. */
    public function yearlySavingPercent(): int
    {
        return $this->hasFixedPrice() && $this->monthly > 0
            ? (int) round($this->yearlySaving() / ($this->monthly * 12) * 100)
            : 0;
    }

    /** What a year costs per month, to one decimal. */
    public function yearlyPerMonth(): float
    {
        return $this->hasFixedPrice() ? round($this->yearly / 12, 1) : 0.0;
    }

    /**
     * The list items as [text, soon]: a plain string is a delivered feature, and an array
     * with soon => true is something not built yet, shown with a "coming soon" label.
     *
     * @return list<array{text: string, soon: bool}>
     */
    public function items(): array
    {
        return array_map(
            fn ($point) => is_array($point)
                ? ['text' => (string) ($point['text'] ?? ''), 'soon' => (bool) ($point['soon'] ?? false)]
                : ['text' => (string) $point, 'soon' => false],
            (array) $this->points,
        );
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort');
    }
}
