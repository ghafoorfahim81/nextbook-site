<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public array $translatable = ['title', 'excerpt', 'content'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'published_at' => 'datetime'];
    }

    /** Live posts, newest first. A post dated in the future waits until that day. */
    public function scopeLive(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at');
    }

    public function isLive(): bool
    {
        return $this->is_published && $this->published_at && $this->published_at->lte(now());
    }

    /** Whole minutes to read at about 200 words a minute, never less than one. Counts words in any script. */
    public function readingMinutes(): int
    {
        $words = preg_split('/\s+/u', trim(strip_tags((string) $this->content)), -1, PREG_SPLIT_NO_EMPTY);

        return max(1, (int) ceil(count($words) / 200));
    }
}
