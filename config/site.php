<?php

return [

    'name' => 'NextBook',

    // Every customer has their own subdomain of this domain (acme.nextbook.af) and signs
    // in there. The "Sign in" page asks for the name and sends them on.
    'tenant_domain' => env('SITE_TENANT_DOMAIN', 'nextbook.af'),
    'tenant_scheme' => env('SITE_TENANT_SCHEME', 'https'),

    'phones' => [
        '+93 72 850 7537',
        '+93 77 350 2152',
    ],

    'emails' => [
        'general' => 'info@nextbook.af',
        'sales' => 'sales@nextbook.af',
    ],

    // Who is told when a visitor sends a demo request or a message (comma-separated in .env).
    // The sender is MAIL_FROM_ADDRESS. A failure to send never loses the request: it is in the admin anyway.
    'leads_to' => array_values(array_filter(array_map('trim', explode(',', (string) env('SITE_LEADS_TO', 'sales@nextbook.af'))))),

    // Visitor statistics. Empty means none. For Plausible: SITE_ANALYTICS_SRC=https://plausible.io/js/script.js
    // and SITE_ANALYTICS_DOMAIN=nextbook.af
    'analytics' => [
        'src' => env('SITE_ANALYTICS_SRC'),
        'domain' => env('SITE_ANALYTICS_DOMAIN', 'nextbook.af'),
    ],

    // Search engine ownership tags: the content value Google Search Console and Bing Webmaster Tools give you.
    'verification' => [
        'google' => env('SITE_GOOGLE_VERIFICATION'),
        'bing' => env('SITE_BING_VERIFICATION'),
    ],

    // Digits only, country code first. Leave empty to hide the WhatsApp button.
    'whatsapp' => env('SITE_WHATSAPP', '93728507537'),

    // Languages the visitor can switch between.
    'locales' => [
        'fa' => ['name' => 'دری', 'dir' => 'rtl'],
        'ps' => ['name' => 'پښتو', 'dir' => 'rtl'],
        'en' => ['name' => 'English', 'dir' => 'ltr'],
    ],

    'default_locale' => 'fa',

    // Business types offered in the demo request form. Keys match the ERP's BusinessType enum.
    'business_types' => [
        'pharmacy', 'supermarket', 'grocery', 'retail', 'clothing', 'mobile', 'computer', 'electronics',
        'jewellery', 'carpet', 'ply_carpet', 'automobile', 'book', 'manufacturing', 'pharma_manufacturing', 'hotel',
        'accounting', 'other',
    ],
];
