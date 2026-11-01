@extends('layouts.app')

@section('content')
@php($appUrl = rtrim(config('site.app_url'), '/'))

{{-- Hero --}}
<section class="relative overflow-hidden bg-gradient-to-b from-brand-50 via-white to-white">
    <div class="container-page grid items-center gap-10 py-14 sm:py-20 lg:grid-cols-2">
        <div>
            <p class="eyebrow">{{ __('site.hero.eyebrow') }}</p>
            <h1 class="text-4xl leading-tight sm:text-5xl">{{ __('site.hero.title') }}</h1>
            <p class="mt-5 max-w-xl text-lg text-slate-600">{{ __('site.hero.text') }}</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('demo') }}" class="btn btn-primary px-6 py-3 text-base">{{ __('site.hero.cta_demo') }}</a>
                <a href="{{ route('features.index') }}" class="btn btn-outline px-6 py-3 text-base">{{ __('site.hero.cta_features') }}</a>
            </div>
            <ul class="mt-8 flex flex-wrap gap-2">
                @foreach (__('site.hero.chips') as $chip)
                    <li class="rounded-full border border-brand-100 bg-white px-3 py-1 text-xs font-medium text-brand-700">{{ $chip }}</li>
                @endforeach
            </ul>
        </div>
        <div class="flex justify-center">
            <div class="relative w-full max-w-md rounded-3xl border border-brand-100 bg-white p-8 shadow-xl">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('site.name') }}" class="mx-auto h-40 w-auto">
                <div class="mt-6 grid grid-cols-3 gap-3 text-center text-xs font-medium text-slate-600">
                    <div class="rounded-xl bg-brand-50 p-3"><x-lucide-boxes class="mx-auto mb-1 h-6 w-6 text-brand-500"/>{{ $features->firstWhere('slug', 'inventory')?->title }}</div>
                    <div class="rounded-xl bg-brand-50 p-3"><x-lucide-shopping-cart class="mx-auto mb-1 h-6 w-6 text-brand-500"/>{{ $features->firstWhere('slug', 'sales')?->title }}</div>
                    <div class="rounded-xl bg-brand-50 p-3"><x-lucide-calculator class="mx-auto mb-1 h-6 w-6 text-brand-500"/>{{ $features->firstWhere('slug', 'accounting')?->title }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Features --}}
<section class="section">
    <div class="container-page">
        <div class="mb-10 max-w-2xl">
            <p class="eyebrow">{{ __('site.home.features_eyebrow') }}</p>
            <h2 class="text-3xl">{{ __('site.home.features_title') }}</h2>
        </div>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($features->take(6) as $feature)
                <a href="{{ route('features.show', $feature) }}" class="card group transition hover:border-brand-300 hover:shadow-md">
                    <span class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                        <x-dynamic-component :component="'lucide-'.$feature->icon" class="h-6 w-6"/>
                    </span>
                    <h3 class="text-lg">{{ $feature->title }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $feature->summary }}</p>
                </a>
            @endforeach
        </div>
        <div class="mt-8"><a href="{{ route('features.index') }}" class="btn btn-outline">{{ __('site.home.features_all') }}</a></div>
    </div>
</section>

{{-- Industries --}}
<section class="section bg-slate-50">
    <div class="container-page">
        <div class="mb-10 max-w-2xl">
            <p class="eyebrow">{{ __('site.home.industries_eyebrow') }}</p>
            <h2 class="text-3xl">{{ __('site.home.industries_title') }}</h2>
            <p class="mt-3 text-slate-600">{{ __('site.home.industries_text') }}</p>
        </div>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($industries->take(8) as $industry)
                <a href="{{ route('industries.show', $industry) }}" class="card flex items-center gap-3 p-4 transition hover:border-brand-300 hover:shadow-md">
                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                        <x-dynamic-component :component="'lucide-'.$industry->icon" class="h-5 w-5"/>
                    </span>
                    <span class="text-sm font-bold text-slate-800">{{ $industry->title }}</span>
                </a>
            @endforeach
        </div>
        <div class="mt-8"><a href="{{ route('industries.index') }}" class="btn btn-outline">{{ __('site.home.industries_all') }}</a></div>
    </div>
</section>

{{-- Trust --}}
<section class="section">
    <div class="container-page">
        <h2 class="mb-10 text-3xl">{{ __('site.home.trust_title') }}</h2>
        <div class="grid gap-5 md:grid-cols-3">
            @foreach (__('site.home.trust') as $item)
                <div class="card">
                    <x-dynamic-component :component="'lucide-'.$item['icon']" class="mb-3 h-8 w-8 text-brand-500"/>
                    <h3 class="text-lg">{{ $item['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $item['text'] }}</p>
                </div>
            @endforeach
        </div>
        <div class="mt-6"><a href="{{ route('security') }}" class="font-bold text-brand-600 hover:underline">{{ __('site.footer.security') }} ←</a></div>
    </div>
</section>

@if ($faqs->isNotEmpty())
<section class="section bg-slate-50">
    <div class="container-page max-w-3xl">
        <h2 class="mb-8 text-3xl">{{ __('site.home.faq_title') }}</h2>
        @include('partials.faq-list', ['faqs' => $faqs, 'open' => true])
    </div>
</section>
@endif

@include('partials.cta-band')
@endsection
