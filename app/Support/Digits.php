<?php

namespace App\Support;

class Digits
{
    private const EASTERN = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    /** A number with at most $decimals decimals (none when it is whole), in the visitor's own digits and decimal mark. */
    public static function number(float|int $value, int $decimals = 1): string
    {
        $text = rtrim(rtrim(number_format($value, $decimals, '.', ''), '0'), '.');

        return self::usesEasternDigits() ? self::localize(str_replace('.', '٫', $text)) : $text;
    }

    /** Dari and Pashto both read Eastern Arabic (Persian) digits. */
    private static function usesEasternDigits(): bool
    {
        return in_array(app()->getLocale(), ['fa', 'ps'], true);
    }

    /** Numbers read in the visitor's own digits: Persian digits on the Dari pages, Latin elsewhere. */
    public static function localize(int|string $value): string
    {
        $value = (string) $value;

        return self::usesEasternDigits() ? strtr($value, array_combine(range(0, 9), self::EASTERN)) : $value;
    }
}
