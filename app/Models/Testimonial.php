<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class Testimonial extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public array $translatable = ['name', 'role', 'quote'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'attachments' => 'array'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('sort');
    }

    /** A translatable field in the visitor's language, or in whichever language it was written. */
    public function text(string $field): ?string
    {
        $values = array_filter($this->getTranslations($field), fn ($v) => filled($v));

        return $values[app()->getLocale()] ?? ($values ? reset($values) : null);
    }

    /** @return array<int, array{type: string, url: string}> image, video or audio */
    public function media(): array
    {
        return collect($this->attachments ?? [])->map(function (string $path) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $type = match (true) {
                in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true) => 'image',
                in_array($ext, ['mp4', 'webm', 'mov'], true) => 'video',
                in_array($ext, ['mp3', 'm4a', 'wav', 'ogg', 'oga', 'weba'], true) => 'audio',
                default => null,
            };

            return $type ? ['type' => $type, 'url' => Storage::disk('public')->url($path)] : null;
        })->filter()->values()->all();
    }
}
