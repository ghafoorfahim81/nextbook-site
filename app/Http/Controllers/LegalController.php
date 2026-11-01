<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

/**
 * The Terms of Service and Privacy Policy, copied from the ERP's resources/markdown so
 * the two sites say the same thing. A language without its own copy falls back to English.
 */
class LegalController extends Controller
{
    public function terms()
    {
        return $this->document('terms', 'footer.terms');
    }

    public function privacy()
    {
        return $this->document('policy', 'footer.privacy');
    }

    private function document(string $name, string $titleKey)
    {
        $locale = app()->getLocale();
        $path = resource_path("markdown/{$locale}/{$name}.md");
        $translated = is_file($path);

        if (! $translated) {
            $path = resource_path("markdown/{$name}.md");
        }

        $bodyLocale = $translated ? $locale : 'en';

        return view('pages.legal', [
            'heading' => __('site.'.$titleKey),
            'body' => Str::markdown(file_get_contents($path), ['html_input' => 'strip']),
            'translated' => $translated || $locale === 'en',
            'bodyDir' => config("site.locales.{$bodyLocale}.dir", 'ltr'),
        ]);
    }
}
