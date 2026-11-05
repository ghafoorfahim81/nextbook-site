<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Morilog\Jalali\Jalalian;

class PostDate
{
    /**
     * The Solar Hijri (shamsi) months by the names Afghans use, in Dari and in Pashto. They fall on the same
     * dates as the Iranian ones (Hamal is Farvardin, Mizan is Mehr, Aqrab is Aban), only the names differ.
     */
    private const MONTHS = [
        'fa' => ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'],
        'ps' => ['وری', 'غویی', 'غبرګولی', 'چنګاښ', 'زمری', 'وږی', 'تله', 'لړم', 'لیندۍ', 'مرغومی', 'سلواغه', 'کب'],
    ];

    /** A date in the visitor's calendar: Afghan Jalali (with Persian digits) on the Dari and Pashto pages, Gregorian otherwise. */
    public static function format(?CarbonInterface $date): string
    {
        if (! $date) {
            return '';
        }

        $locale = app()->getLocale();

        if (! isset(self::MONTHS[$locale])) {
            return $date->locale('en')->translatedFormat('j F Y');
        }

        $jalali = Jalalian::fromCarbon($date);

        return Digits::localize($jalali->getDay().' '.self::MONTHS[$locale][$jalali->getMonth() - 1].' '.$jalali->getYear());
    }
}
