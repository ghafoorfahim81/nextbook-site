<?php

namespace App\Support;

class Digits
{
    private const EASTERN = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    /** A number with at most $decimals decimals (none when it is whole), in the visitor's own digits and decimal mark. */
    public static function number(float|int $value, int $decimals = 1): string
    {
        $text = rtrim(rtrim(number_format($value, $decimals, '.', ''), '0'), '.');

        return app()->getLocale() === 'fa' ? self::localize(str_replace('.', '٫', $text)) : $text;
    }

    /** Numbers read in the visitor's own digits: Persian digits on the Dari pages, Latin elsewhere. */
    public static function localize(int|string $value): string
    {
        $value = (string) $value;

        return app()->getLocale() === 'fa' ? strtr($value, array_combine(range(0, 9), self::EASTERN)) : $value;
    }
}
