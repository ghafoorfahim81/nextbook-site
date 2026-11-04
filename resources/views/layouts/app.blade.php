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
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    @isset($robots)<meta name="robots" content="{{ $robots }}">@endisset
    <meta name="theme-color" content="#9142e0">
    {{-- How a link looks when it is shared in WhatsApp, Facebook, Telegram and so on. --}}
    <meta property="og:site_name" content="{{ config('site.name') }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:url" content="{{ url($path) }}">
    <meta property="og:locale" content="{{ $locale === 'fa' ? 'fa_AF' : 'en_US' }}">
    @foreach (array_keys(config('site.locales')) as $code)
        @if ($code !== $locale)<meta property="og:locale:alternate" content="{{ $code === 'fa' ? 'fa_AF' : 'en_US' }}">@endif
    @endforeach
    <meta property="og:image" content="{{ asset('images/og.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="{{ url($path) }}">    @foreach (config('site.locales') as $code => $meta)
        <link rel="alternate" hreflang="{{ $code }}" href="{{ url('/'.$code.$rest) }}">
    @endforeach
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('icon-192.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => config('site.name'),
        'url' => url('/'),
        'logo' => asset('icon-512.png'),
        'email' => config('site.emails.general'),
        'contactPoint' => array_map(fn ($phone) => [
            '@type' => 'ContactPoint', 'contactType' => 'sales', 'telephone' => preg_replace('/\s+/', '', $phone),
        ], config('site.phones')),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
    @stack('jsonld')    <link rel="preload" href="{{ Vite::asset($locale === 'fa' ? 'resources/fonts/iransans-regular.ttf' : 'resources/fonts/poppins-regular.ttf') }}" as="font" type="font/ttf" crossorigin>
    <meta name="color-scheme" content="light dark">
    {{-- Applies the saved (or the system) theme before the first paint, so a dark page never flashes white. --}}
    <script>
        (function () {
            try {
                document.documentElement.classList.add('js');
                var saved = localStorage.getItem('theme');
                if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) { /* storage blocked: the page stays light */ }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (config('site.analytics.src'))
        {{-- Privacy-friendly visitor statistics (Plausible or similar): no cookies. Off unless set in .env. --}}
        <script defer data-domain="{{ config('site.analytics.domain') }}" src="{{ config('site.analytics.src') }}"></script>
    @endif
</head>
<body class="flex min-h-screen flex-col">

<header class="sticky top-0 z-40 border-b border-brand-100 bg-surface/85 backdrop-blur-md">
    <div class="container-page flex h-16 items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2" aria-label="{{ config('site.name') }}">
            <img src="{{ asset('images/logo.png') }}" alt="" width="56" height="40" class="h-10 w-auto shrink-0">
        </a>

        <nav class="hidden min-w-0 items-center gap-0.5 xl:flex 2xl:gap-1" aria-label="Main">
            @foreach ([
                ['features.index', 'features'], ['industries.index', 'industries'], ['pricing', 'pricing'],
                ['help.index', 'help'], ['blog.index', 'blog'], ['contact', 'contact'],
            ] as [$routeName, $key])
                <a href="{{ route($routeName) }}"
                   class="whitespace-nowrap rounded-full px-3.5 py-2 text-sm font-medium {{ request()->routeIs(Str::before($routeName, '.').'*') ? 'bg-brand-50 text-brand-700 ring-1 ring-brand-200' : 'text-slate-600 hover:bg-brand-50 hover:text-brand-700' }}">
                    {{ __('site.nav.'.$key) }}
                </a>
            @endforeach
        </nav>

        <div class="hidden shrink-0 items-center gap-2 xl:flex">
            @include('partials.header-tools')
            @include('partials.lang-switch')
            <a href="{{ route('login') }}" class="btn btn-sm btn-outline">{{ __('site.nav.login') }}</a>
            <a href="{{ route('demo') }}" class="btn btn-sm btn-primary">{{ __('site.nav.demo') }}</a>
        </div>

        <div class="flex items-center gap-1 xl:hidden">
            @include('partials.header-tools')
            <button id="nav-toggle" type="button" class="rounded-lg p-2 text-slate-700 hover:bg-slate-100"
                    aria-expanded="false" aria-controls="nav-menu" aria-label="{{ __('site.nav.menu') }}">
                <x-lucide-menu class="h-6 w-6"/>
            </button>
        </div>
    </div>

    <div id="nav-menu" class="hidden border-t border-slate-200 bg-surface xl:hidden">
        <div class="container-page flex flex-col gap-1 py-3">
            @foreach ([
                ['features.index', 'features'], ['industries.index', 'industries'], ['pricing', 'pricing'],
                ['help.index', 'help'], ['blog.index', 'blog'], ['contact', 'contact'],
            ] as [$routeName, $key])
                <a href="{{ route($routeName) }}" class="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    {{ __('site.nav.'.$key) }}
                </a>
            @endforeach
            <div class="mt-2 flex flex-wrap items-center gap-2">
                @include('partials.lang-switch')
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline">{{ __('site.nav.login') }}</a>
                <a href="{{ route('demo') }}" class="btn btn-sm btn-primary">{{ __('site.nav.demo') }}</a>
            </div>
        </div>
    </div>
</header>

<main class="flex-1">
    @yield('content')
</main>

<footer class="relative mt-16 overflow-hidden bg-brand-900 text-brand-100">
    <div aria-hidden="true" class="bg-grid-light fade-edges absolute inset-0 opacity-60"></div>
    <div class="container-page relative pt-16">
        <div class="max-w-3xl">
            <span class="mb-5 block h-1 w-16 rounded-full bg-accent-500"></span>
            <p class="text-2xl font-bold leading-relaxed text-white sm:text-4xl">{{ __('site.story.footer_line') }}</p>
        </div>
        <div class="mt-12 grid gap-10 border-t border-white/15 py-12 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('site.name') }}" class="h-12 w-auto rounded-lg bg-white/95 p-1.5">
                <p class="mt-4 text-sm text-brand-200">{{ __('site.tagline') }}</p>
            </div>
            <div>
                <h4 class="mb-3 text-sm text-white">{{ __('site.footer.product') }}</h4>
                <ul class="space-y-2 text-sm text-brand-200">
                    <li><a class="hover:text-accent-400" href="{{ route('features.index') }}">{{ __('site.nav.features') }}</a></li>
                    <li><a class="hover:text-accent-400" href="{{ route('industries.index') }}">{{ __('site.nav.industries') }}</a></li>
                    <li><a class="hover:text-accent-400" href="{{ route('pricing') }}">{{ __('site.nav.pricing') }}</a></li>
                    <li><a class="hover:text-accent-400" href="{{ route('security') }}">{{ __('site.footer.security') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="mb-3 text-sm text-white">{{ __('site.footer.support') }}</h4>
                <ul class="space-y-2 text-sm text-brand-200">
                    <li><a class="hover:text-accent-400" href="{{ route('help.index') }}">{{ __('site.nav.help') }}</a></li>
                    <li><a class="hover:text-accent-400" href="{{ route('blog.index') }}">{{ __('site.nav.blog') }}</a></li>
                    <li><a class="hover:text-accent-400" href="{{ route('faq') }}">{{ __('site.footer.faq') }}</a></li>
                    <li><a class="hover:text-accent-400" href="{{ route('contact') }}">{{ __('site.nav.contact') }}</a></li>
                    <li><a class="hover:text-accent-400" href="{{ route('about') }}">{{ __('site.footer.about') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="mb-3 text-sm text-white">{{ __('site.contact.title') }}</h4>
                <ul class="space-y-2 text-sm text-brand-200">
                    @foreach ($phones as $phone)
                        <li><a dir="ltr" class="inline-flex items-center gap-2 hover:text-accent-400" href="tel:{{ preg_replace('/\s+/', '', $phone) }}"><x-lucide-phone class="h-4 w-4"/>{{ $phone }}</a></li>
                    @endforeach
                    @foreach ($emails as $email)
                        <li><a dir="ltr" class="inline-flex items-center gap-2 hover:text-accent-400" href="mailto:{{ $email }}"><x-lucide-mail class="h-4 w-4"/>{{ $email }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="border-t border-white/15 py-5 text-center text-xs text-brand-300">
            <a class="hover:text-accent-400" href="{{ route('legal.terms') }}">{{ __('site.footer.terms') }}</a>
            <span class="mx-2">·</span>
            <a class="hover:text-accent-400" href="{{ route('legal.privacy') }}">{{ __('site.footer.privacy') }}</a>
            <span class="mx-2">·</span>
            © {{ date('Y') }} {{ config('site.name') }}. {{ __('site.footer.rights') }}
        </div>
    </div>
</footer>

@if ($whatsapp)
    <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener"
       class="fixed bottom-5 end-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-green-500 text-white shadow-lg hover:bg-green-600"
       aria-label="{{ __('site.contact.whatsapp') }}">
        <x-lucide-message-circle class="h-7 w-7"/>
    </a>
@endif

@include('partials.search-dialog')

</body>
</html>
