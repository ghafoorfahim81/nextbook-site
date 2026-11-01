<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Morilog\Jalali\Jalalian;

class PostDate
{
    /** A date in the visitor's calendar: Jalali (with Persian digits) on the Dari pages, Gregorian otherwise. */
    public static function format(?CarbonInterface $date): string
    {
        if (! $date) {
            return '';
        }

        return app()->getLocale() === 'fa'
            ? Digits::localize(Jalalian::fromCarbon($date)->format('j F Y'))
            : $date->locale('en')->translatedFormat('j F Y');
    }
}
