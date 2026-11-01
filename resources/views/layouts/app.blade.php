@php
    $locale = app()->getLocale();
    $dir = config("site.locales.$locale.dir", 'rtl');
    $path = '/'.ltrim(request()->path(), '/');
    $rest = preg_replace('#^/[a-z]{2}(?=/|$)#', '', $path);
    $title = isset($title) ? $title.' · '.config('site.name') : config('site.name').' · '.__('site.tagline');
    $description = $description ?? __('site.meta_description');
    $phones = config('site.phones');
    $emails = config('site.emails');
    $whatsapp = config('site.whatsapp');
    $appUrl = rtrim(config('site.app_url'), '/');
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <link rel="canonical" href="{{ url($path) }}">
    @foreach (config('site.locales') as $code => $meta)
        <link rel="alternate" hreflang="{{ $code }}" href="{{ url('/'.$code.$rest) }}">
    @endforeach
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link rel="preload" href="{{ asset('fonts/iranyekan-regular.ttf') }}" as="font" type="font/ttf" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col">

<header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur">
    <div class="container-page flex h-16 items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2" aria-label="{{ config('site.name') }}">
            <img src="{{ asset('images/logo.png') }}" alt="" class="h-10 w-auto">
        </a>

        <nav class="hidden items-center gap-1 lg:flex" aria-label="Main">
            @foreach ([
                ['features.index', 'features'], ['industries.index', 'industries'], ['pricing', 'pricing'],
                ['help.index', 'help'], ['contact', 'contact'],
            ] as [$routeName, $key])
                <a href="{{ route($routeName) }}"
                   class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs(Str::before($routeName, '.').'*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    {{ __('site.nav.'.$key) }}
                </a>
            @endforeach
        </nav>

        <div class="hidden items-center gap-2 lg:flex">
            @include('partials.lang-switch')
            <a href="{{ $appUrl }}/login" class="btn btn-outline">{{ __('site.nav.login') }}</a>
            <a href="{{ route('demo') }}" class="btn btn-primary">{{ __('site.nav.demo') }}</a>
        </div>

        <button id="nav-toggle" type="button" class="rounded-lg p-2 text-slate-700 hover:bg-slate-100 lg:hidden"
                aria-expanded="false" aria-controls="nav-menu" aria-label="{{ __('site.nav.menu') }}">
            <x-lucide-menu class="h-6 w-6"/>
        </button>
    </div>

    <div id="nav-menu" class="hidden border-t border-slate-200 bg-white lg:hidden">
        <div class="container-page flex flex-col gap-1 py-3">
            @foreach ([
                ['features.index', 'features'], ['industries.index', 'industries'], ['pricing', 'pricing'],
                ['help.index', 'help'], ['contact', 'contact'],
            ] as [$routeName, $key])
                <a href="{{ route($routeName) }}" class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    {{ __('site.nav.'.$key) }}
                </a>
            @endforeach
            <div class="mt-2 flex flex-wrap items-center gap-2">
                @include('partials.lang-switch')
                <a href="{{ $appUrl }}/login" class="btn btn-outline">{{ __('site.nav.login') }}</a>
                <a href="{{ route('demo') }}" class="btn btn-primary">{{ __('site.nav.demo') }}</a>
            </div>
        </div>
    </div>
</header>

<main class="flex-1">
    @yield('content')
</main>

<footer class="mt-16 border-t border-slate-200 bg-slate-50">
    <div class="container-page grid gap-10 py-12 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('site.name') }}" class="h-12 w-auto">
            <p class="mt-3 text-sm text-slate-600">{{ __('site.tagline') }}</p>
        </div>
        <div>
            <h4 class="mb-3 text-sm">{{ __('site.footer.product') }}</h4>
            <ul class="space-y-2 text-sm text-slate-600">
                <li><a class="hover:text-brand-600" href="{{ route('features.index') }}">{{ __('site.nav.features') }}</a></li>
                <li><a class="hover:text-brand-600" href="{{ route('industries.index') }}">{{ __('site.nav.industries') }}</a></li>
                <li><a class="hover:text-brand-600" href="{{ route('pricing') }}">{{ __('site.nav.pricing') }}</a></li>
                <li><a class="hover:text-brand-600" href="{{ route('security') }}">{{ __('site.footer.security') }}</a></li>
            </ul>
        </div>
        <div>
            <h4 class="mb-3 text-sm">{{ __('site.footer.support') }}</h4>
            <ul class="space-y-2 text-sm text-slate-600">
                <li><a class="hover:text-brand-600" href="{{ route('help.index') }}">{{ __('site.nav.help') }}</a></li>
                <li><a class="hover:text-brand-600" href="{{ route('faq') }}">{{ __('site.footer.faq') }}</a></li>
                <li><a class="hover:text-brand-600" href="{{ route('contact') }}">{{ __('site.nav.contact') }}</a></li>
                <li><a class="hover:text-brand-600" href="{{ route('about') }}">{{ __('site.footer.about') }}</a></li>
            </ul>
        </div>
        <div>
            <h4 class="mb-3 text-sm">{{ __('site.contact.title') }}</h4>
            <ul class="space-y-2 text-sm text-slate-600">
                @foreach ($phones as $phone)
                    <li><a dir="ltr" class="inline-flex items-center gap-2 hover:text-brand-600" href="tel:{{ preg_replace('/\s+/', '', $phone) }}"><x-lucide-phone class="h-4 w-4"/>{{ $phone }}</a></li>
                @endforeach
                @foreach ($emails as $email)
                    <li><a dir="ltr" class="inline-flex items-center gap-2 hover:text-brand-600" href="mailto:{{ $email }}"><x-lucide-mail class="h-4 w-4"/>{{ $email }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="border-t border-slate-200 py-4 text-center text-xs text-slate-500">
        © {{ date('Y') }} {{ config('site.name') }}. {{ __('site.footer.rights') }}
    </div>
</footer>

@if ($whatsapp)
    <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener"
       class="fixed bottom-5 end-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-green-500 text-white shadow-lg hover:bg-green-600"
       aria-label="{{ __('site.contact.whatsapp') }}">
        <x-lucide-message-circle class="h-7 w-7"/>
    </a>
@endif

</body>
</html>
