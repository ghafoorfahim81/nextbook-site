<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class HelpCategory extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public array $translatable = ['title', 'summary'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function articles(): HasMany
    {
        return $this->hasMany(HelpArticle::class)->where('is_published', true)->orderBy('sort');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort');
    }
}
