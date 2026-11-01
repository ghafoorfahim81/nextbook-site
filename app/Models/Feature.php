<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Feature extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public array $translatable = ['title', 'summary', 'overview', 'highlights', 'steps', 'points'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    /** The overview as paragraphs: authors separate them with a blank line. */
    public function overviewParagraphs(): array
    {
        return array_values(array_filter(preg_split('/\R{2,}/', trim((string) $this->overview))));
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort');
    }
}
