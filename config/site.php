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

    // Digits only, country code first. Leave empty to hide the WhatsApp button.
    'whatsapp' => env('SITE_WHATSAPP', '93728507537'),

    // Languages the visitor can switch between. 'ps' is ready in the schema and
    // joins this list once its interface texts and content are translated.
    'locales' => [
        'fa' => ['name' => 'دری', 'dir' => 'rtl'],
        'en' => ['name' => 'English', 'dir' => 'ltr'],
    ],

    'default_locale' => 'fa',

    // Business types offered in the demo request form. Keys match the ERP's BusinessType enum.
    'business_types' => [
        'pharmacy', 'supermarket', 'grocery', 'retail', 'clothing', 'mobile', 'computer', 'electronics',
        'jewellery', 'carpet', 'automobile', 'book', 'manufacturing', 'pharma_manufacturing', 'hotel',
        'accounting', 'other',
    ],
];
