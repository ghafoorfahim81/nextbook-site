@extends('layouts.app', ['title' => $feature->title, 'description' => $feature->summary])

@section('content')
@php
    use App\Support\Digits;
    $paragraphs = $feature->overviewParagraphs();
    $highlights = (array) $feature->highlights;
    $steps = (array) $feature->steps;
    $points = (array) $feature->points;
    $soon = $feature->status === 'soon';
@endphp

@include('partials.page-head', [
    'heading' => $feature->title,
    'intro' => $feature->summary,
    'icon' => $feature->icon,
    'crumb' => ['url' => route('features.index'), 'label' => __('site.features.title')],
    'actions' => $soon ? null : '<a href="'.e(route('demo')).'" class="btn btn-primary">'.e(__('site.nav.demo')).'</a>'
        .($feature->help_slug ? '<a href="'.e(route('help.category', ['category' => $feature->help_slug])).'" class="btn btn-outline">'.e(__('site.features.guide_btn')).'</a>' : ''),
])

{{-- Overview --}}
<section class="section pb-8 sm:pb-10">
    <div class="container-page grid gap-10 lg:grid-cols-3">
        <div class="space-y-5 text-lg text-slate-700 lg:col-span-2">
            @if ($soon)
                <div class="rounded-xl border-s-4 border-accent-500 bg-amber-50 px-4 py-3 text-base text-amber-900">
                    <b>{{ __('site.features.soon') }}.</b> {{ __('site.features.soon_note') }}
                </div>
            @endif
            @foreach ($paragraphs as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </div>

        @if ($points)
            <aside>
                <div class="card bg-brand-50/60">
                    <h2 class="mb-3 text-base">{{ __('site.features.also') }}</h2>
                    <ul class="space-y-2 text-sm">
                        @foreach ($points as $point)
                            <li class="flex items-start gap-2"><x-lucide-check class="mt-0.5 h-4 w-4 shrink-0 text-brand-500"/>{{ $point }}</li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        @endif
    </div>
</section>

{{-- Highlights --}}
@if ($highlights)
<section class="section relative isolate bg-slate-50">
    <div aria-hidden="true" class="bg-dots fade-edges absolute inset-0 -z-10 opacity-50"></div>
    <div class="container-page">
        <h2 class="mb-10 text-2xl sm:text-3xl">{{ __('site.features.highlights_title') }}</h2>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($highlights as $highlight)
                <div class="card card-hover">
                    <span class="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-brand-50 text-brand-600"><x-lucide-check class="h-5 w-5"/></span>
                    <h3 class="text-lg">{{ $highlight['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $highlight['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- A working day --}}
@if ($steps)
<section class="section">
    <div class="container-page">
        <div class="mb-10 max-w-2xl">
            <h2 class="text-2xl sm:text-3xl">{{ __('site.features.day_title') }}</h2>
            <p class="mt-2 text-slate-600">{{ __('site.features.day_text') }}</p>
        </div>
        <ol class="grid gap-6 md:grid-cols-{{ min(count($steps), 4) }}">
            @foreach ($steps as $step)
                <li class="relative">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-brand-500 text-lg font-extrabold text-white shadow-md shadow-brand-500/30">{{ Digits::localize($loop->iteration) }}</span>
                    @unless ($loop->last)
                        <span aria-hidden="true" class="absolute top-5 hidden h-px w-[calc(100%-3.5rem)] bg-brand-200 md:block ltr:left-14 rtl:right-14"></span>
                    @endunless
                    <p class="mt-4 text-slate-700">{{ $step }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>
@endif

{{-- The guide, and who relies on this module --}}
@if ($feature->help_slug || $industries->isNotEmpty())
<section class="pb-4">
    <div class="container-page grid gap-6 lg:grid-cols-2">
        @if ($feature->help_slug)
            <div class="card bg-gradient-to-br from-brand-50 to-surface">
                <x-lucide-book-open class="mb-3 h-8 w-8 text-brand-500"/>
                <h2 class="text-lg">{{ __('site.features.guide') }}</h2>
                <p class="mt-1 text-sm text-slate-600">{{ __('site.features.guide_text') }}</p>
                <a href="{{ route('help.category', ['category' => $feature->help_slug]) }}" class="btn btn-primary mt-4">{{ __('site.features.guide_btn') }}</a>
            </div>
        @endif
        @if ($industries->isNotEmpty())
            <div class="card">
                <h2 class="mb-4 text-lg">{{ __('site.features.used_by') }}</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach ($industries as $industry)
                        <a href="{{ route('industries.show', $industry) }}" class="inline-flex items-center gap-2 rounded-full border border-brand-100 bg-brand-50 px-3 py-1.5 text-sm font-medium text-brand-800 hover:bg-brand-100">
                            <x-dynamic-component :component="'lucide-'.$industry->icon" class="h-4 w-4"/>{{ $industry->title }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endif

{{-- Other modules --}}
<section class="section pb-0">
    <div class="container-page">
        <h2 class="mb-5 text-xl">{{ __('site.features.other') }}</h2>
        <div class="flex flex-wrap gap-2">
            @foreach ($others as $other)
                <a href="{{ route('features.show', $other) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-surface px-4 py-2.5 text-sm font-medium text-slate-700 hover:border-brand-300 hover:text-brand-700">
                    <x-dynamic-component :component="'lucide-'.$other->icon" class="h-4 w-4 text-brand-500"/>{{ $other->title }}
                </a>
            @endforeach
        </div>
    </div>
</section>

@include('partials.cta-band')
@endsection
