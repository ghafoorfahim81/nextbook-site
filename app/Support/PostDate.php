<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Morilog\Jalali\Jalalian;

class PostDate
{
    /** A date in the visitor's calendar: Jalali (with Persian digits) on the Dari and Pashto pages, Gregorian otherwise. */
    public static function format(?CarbonInterface $date): string
    {
        if (! $date) {
            return '';
        }

        return in_array(app()->getLocale(), ['fa', 'ps'], true)
            ? Digits::localize(Jalalian::fromCarbon($date)->format('j F Y'))
            : $date->locale('en')->translatedFormat('j F Y');
    }
}
