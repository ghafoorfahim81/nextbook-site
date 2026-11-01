<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PricingPlan extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public array $translatable = ['name', 'description', 'price_label', 'period', 'points'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'is_featured' => 'boolean'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort');
    }
}
