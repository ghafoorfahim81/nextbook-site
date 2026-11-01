@extends('layouts.app')

@section('content')
@php
    use App\Support\Digits;
    $locale = app()->getLocale();
    $shot = file_exists(public_path("images/screenshots/dashboard-{$locale}.webp")) ? "dashboard-{$locale}" : 'dashboard-en';
    [$shotW, $shotH] = $locale === 'fa' ? [1085, 799] : [1089, 810];
@endphp

{{-- Hero --}}
<section class="relative isolate overflow-hidden pb-16 sm:pb-24">
    @include('partials.backdrop')

    <div class="container-page pt-14 text-center sm:pt-20">
        <span class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-white/80 px-4 py-1.5 text-sm font-bold text-brand-700 shadow-sm backdrop-blur">
            <span class="h-2 w-2 rounded-full bg-accent-500"></span>{{ __('site.hero.eyebrow') }}
        </span>

        <h1 class="mx-auto mt-6 max-w-4xl text-4xl leading-tight sm:text-6xl">
            {{ __('site.hero.title_a') }}
            <span class="text-gradient">{{ __('site.hero.title_b') }}</span>
        </h1>
        <p class="mx-auto mt-6 max-w-2xl text-lg text-slate-600">{{ __('site.hero.text') }}</p>

        <div class="mt-9 flex flex-wrap justify-center gap-3">
            <a href="{{ route('demo') }}" class="btn btn-primary px-7 py-3 text-base shadow-lg shadow-brand-500/30">{{ __('site.hero.cta_demo') }}</a>
            <a href="{{ route('features.index') }}" class="btn btn-outline px-7 py-3 text-base">{{ __('site.hero.cta_features') }}</a>
        </div>
    </div>

    {{-- The product itself, in a browser window. --}}
    <div class="container-page relative mt-14 max-w-5xl sm:mt-16">
        <div aria-hidden="true" class="absolute inset-x-10 -bottom-6 top-1/3 -z-10 rounded-full bg-brand-400/30 blur-3xl"></div>

        <div class="float-card -top-5 start-2 lg:-start-8"><x-lucide-calculator class="h-5 w-5 text-brand-500"/>{{ __('site.hero.float.accounting') }}</div>
        <div class="float-card top-1/2 -end-2 [animation-delay:-2s] lg:-end-10"><x-lucide-calendar-clock class="h-5 w-5 text-brand-500"/>{{ __('site.hero.float.calendar') }}</div>
        <div class="float-card -bottom-4 start-10 [animation-delay:-4s] lg:start-0"><x-lucide-languages class="h-5 w-5 text-brand-500"/>{{ __('site.hero.float.languages') }}</div>

        <div class="browser browser-tilt">
            <div class="browser-bar" dir="ltr">
                <span class="h-3 w-3 rounded-full bg-red-400"></span>
                <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                <span class="h-3 w-3 rounded-full bg-green-400"></span>
                <span class="mx-auto flex items-center gap-1.5 rounded-md bg-white px-3 py-1 text-xs text-slate-500 ring-1 ring-slate-200">
                    <x-lucide-shield-check class="h-3.5 w-3.5 text-green-600"/>your-company.nextbook.af
                </span>
                <span class="w-12"></span>
            </div>
            <img src="{{ asset("images/screenshots/{$shot}.webp") }}" width="{{ $shotW }}" height="{{ $shotH }}"
                 alt="{{ __('site.hero.shot_alt') }}" fetchpriority="high" class="block w-full">
        </div>

        {{-- The bottom of the screenshot melts into the page. --}}
        <div aria-hidden="true" class="pointer-events-none absolute inset-x-0 bottom-0 h-1/4 bg-gradient-to-t from-white to-transparent"></div>
    </div>
</section>

{{-- Real figures, counted from the content. --}}
<section class="pb-4">
    <div class="container-page">
        <dl class="grid grid-cols-2 gap-px overflow-hidden rounded-2xl border border-slate-200 bg-slate-200 shadow-sm md:grid-cols-4">
            @foreach ([
                ['features', $stats['features']], ['industries', $stats['industries']],
                ['guides', $stats['guides']], ['languages', 3],
            ] as [$key, $value])
                <div class="bg-white px-6 py-7 text-center">
                    <dd class="text-3xl font-bold text-brand-600">{{ Digits::localize($value) }}</dd>
                    <dt class="mt-1 text-sm text-slate-600">{{ __('site.stats.'.$key) }}</dt>
                </div>
            @endforeach
        </dl>
    </div>
</section>

{{-- Features --}}
<section class="section relative isolate">
    <div aria-hidden="true" class="bg-dots fade-edges absolute inset-x-0 top-0 -z-10 h-96 opacity-60"></div>
    <div class="container-page">
        <div class="mb-12 max-w-2xl">
            <p class="eyebrow">{{ __('site.home.features_eyebrow') }}</p>
            <h2 class="text-3xl sm:text-4xl">{{ __('site.home.features_title') }}</h2>
        </div>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($features->take(6) as $feature)
                @include('partials.card-feature', ['feature' => $feature])
            @endforeach
        </div>
        <div class="mt-10"><a href="{{ route('features.index') }}" class="btn btn-outline">{{ __('site.home.features_all') }}</a></div>
    </div>
</section>

{{-- Industries --}}
<section class="section relative isolate bg-slate-50">
    <div aria-hidden="true" class="bg-grid fade-center absolute inset-0 -z-10"></div>
    <div class="container-page">
        <div class="mb-12 max-w-2xl">
            <p class="eyebrow">{{ __('site.home.industries_eyebrow') }}</p>
            <h2 class="text-3xl sm:text-4xl">{{ __('site.home.industries_title') }}</h2>
            <p class="mt-4 text-slate-600">{{ __('site.home.industries_text') }}</p>
        </div>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($industries->take(8) as $industry)
                <a href="{{ route('industries.show', $industry) }}" class="card card-hover flex items-center gap-3 p-4">
                    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                        <x-dynamic-component :component="'lucide-'.$industry->icon" class="h-5 w-5"/>
                    </span>
                    <span class="text-sm font-bold text-slate-800">{{ $industry->title }}</span>
                </a>
            @endforeach
        </div>
        <div class="mt-10"><a href="{{ route('industries.index') }}" class="btn btn-outline">{{ __('site.home.industries_all') }}</a></div>
    </div>
</section>

{{-- How it starts --}}
<section class="section">
    <div class="container-page">
        <div class="mb-12 max-w-2xl">
            <p class="eyebrow">{{ __('site.home.start_eyebrow') }}</p>
            <h2 class="text-3xl sm:text-4xl">{{ __('site.home.start_title') }}</h2>
        </div>
        <ol class="relative grid gap-6 md:grid-cols-3">
            @foreach (__('site.home.start') as $step)
                <li class="card relative">
                    <span class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-brand-500 text-lg font-extrabold text-white">{{ Digits::localize($loop->iteration) }}</span>
                    <h3 class="text-lg">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>
        <div class="mt-8"><a href="{{ route('help.category', ['category' => 'getting-started']) }}" class="font-bold text-brand-600 hover:underline">{{ __('site.home.start_guide') }} ←</a></div>
    </div>
</section>

{{-- Trust --}}
<section class="section relative isolate bg-slate-50">
    <div aria-hidden="true" class="bg-dots fade-edges absolute inset-0 -z-10 opacity-50"></div>
    <div class="container-page">
        <h2 class="mb-10 text-3xl sm:text-4xl">{{ __('site.home.trust_title') }}</h2>
        <div class="grid gap-5 md:grid-cols-3">
            @foreach (__('site.home.trust') as $item)
                <div class="card card-hover">
                    <span class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                        <x-dynamic-component :component="'lucide-'.$item['icon']" class="h-6 w-6"/>
                    </span>
                    <h3 class="text-lg">{{ $item['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $item['text'] }}</p>
                </div>
            @endforeach
        </div>
        <div class="mt-8"><a href="{{ route('security') }}" class="font-bold text-brand-600 hover:underline">{{ __('site.footer.security') }} ←</a></div>
    </div>
</section>

@if ($faqs->isNotEmpty())
<section class="section">
    <div class="container-page max-w-3xl">
        <h2 class="mb-8 text-3xl">{{ __('site.home.faq_title') }}</h2>
        @include('partials.faq-list', ['faqs' => $faqs, 'open' => true])
    </div>
</section>
@endif

@include('partials.cta-band')
@endsection
