<?php

namespace App\Filament\Support;

use Closure;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;

/**
 * Helpers for editing spatie/laravel-translatable columns in Filament.
 *
 * Every translatable column is a JSON object keyed by locale. The form binds one
 * field per locale ("title.fa", "title.en", ...) inside a tab per language, and
 * assigning the resulting array back to the model saves all the translations.
 */
class Translations
{
    /** Languages the admin can write content in. Pashto content is entered here before the public site shows it. */
    public const LOCALES = [
        'fa' => 'دری',
        'en' => 'English',
        'ps' => 'پښتو',
    ];

    /**
     * @param  Closure(string $locale): array  $fields  components for one language
     */
    public static function tabs(Closure $fields): Tabs
    {
        $tabs = [];

        foreach (self::LOCALES as $locale => $label) {
            $tabs[] = Tab::make($label)->schema($fields($locale));
        }

        return Tabs::make('Translations')->tabs($tabs)->columnSpanFull();
    }

    /**
     * The edit form is filled from attributesToArray(), which returns only the current
     * language for translatable columns. Put every language back.
     */
    public static function fill(Model $record, array $data): array
    {
        foreach ($record->translatable as $field) {
            $data[$field] = $record->getTranslations($field);
        }

        return $data;
    }
}
