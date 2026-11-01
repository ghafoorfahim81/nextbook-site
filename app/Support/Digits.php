<?php

namespace App\Support;

class Digits
{
    private const EASTERN = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    /** Numbers read in the visitor's own digits: Persian digits on the Dari pages, Latin elsewhere. */
    public static function localize(int|string $value): string
    {
        $value = (string) $value;

        return app()->getLocale() === 'fa' ? strtr($value, array_combine(range(0, 9), self::EASTERN)) : $value;
    }
}
