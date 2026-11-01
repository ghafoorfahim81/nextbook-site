@extends('layouts.app', ['title' => $industry->title, 'description' => $industry->summary])

@section('content')
@php
    use App\Support\Digits;
    $paragraphs = $industry->overviewParagraphs();
    $highlights = (array) $industry->highlights;
    $steps = (array) $industry->steps;
    $points = (array) $industry->points;
@endphp

@include('partials.page-head', [
    'heading' => $industry->title,
    'intro' => $industry->summary,
    'icon' => $industry->icon,
    'crumb' => ['url' => route('industries.index'), 'label' => __('site.industries.title')],
    'actions' => '<a href="'.e(route('demo', ['type' => $industry->business_type])).'" class="btn btn-primary">'.e(__('site.nav.demo')).'</a>'
        .'<a href="'.e(route('pricing')).'" class="btn btn-outline">'.e(__('site.nav.pricing')).'</a>',
])

{{-- Overview --}}
<section class="section pb-8 sm:pb-10">
    <div class="container-page grid gap-10 lg:grid-cols-3">
        <div class="space-y-5 text-lg text-slate-700 lg:col-span-2">
            @foreach ($paragraphs as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </div>

        @if ($points)
            <aside>
                <div class="card bg-brand-50/60">
                    <h2 class="mb-3 text-base">{{ __('site.industries.also') }}</h2>
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

{{-- What is different for this trade --}}
@if ($highlights)
<section class="section relative isolate bg-slate-50">
    <div aria-hidden="true" class="bg-dots fade-edges absolute inset-0 -z-10 opacity-50"></div>
    <div class="container-page">
        <h2 class="mb-10 text-2xl sm:text-3xl">{{ __('site.industries.highlights_title') }}</h2>
        <div class="grid gap-5 sm:grid-cols-2">
            @foreach ($highlights as $highlight)
                <div class="card card-hover flex gap-4">
                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-brand-500 to-brand-700 text-white"><x-lucide-check class="h-5 w-5"/></span>
                    <div>
                        <h3 class="text-lg">{{ $highlight['title'] }}</h3>
                        <p class="mt-1 text-sm text-slate-600">{{ $highlight['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- A day --}}
@if ($steps)
<section class="section">
    <div class="container-page">
        <h2 class="mb-10 text-2xl sm:text-3xl">{{ __('site.industries.day_title') }}</h2>
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

{{-- The modules this trade uses --}}
@if ($features->isNotEmpty())
<section class="pb-4">
    <div class="container-page">
        <h2 class="mb-6 text-xl">{{ __('site.industries.features_title') }}</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($features as $feature)
                <a href="{{ route('features.show', $feature) }}" class="card card-hover flex items-start gap-3 p-4">
                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600"><x-dynamic-component :component="'lucide-'.$feature->icon" class="h-5 w-5"/></span>
                    <span>
                        <span class="block font-bold text-slate-900">{{ $feature->title }}</span>
                        <span class="mt-0.5 block text-sm text-slate-600">{{ $feature->summary }}</span>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Other industries --}}
<section class="section pb-0">
    <div class="container-page">
        <h2 class="mb-5 text-xl">{{ __('site.industries.other') }}</h2>
        <div class="flex flex-wrap gap-2">
            @foreach ($others as $other)
                <a href="{{ route('industries.show', $other) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-surface px-4 py-2.5 text-sm font-medium text-slate-700 hover:border-brand-300 hover:text-brand-700">
                    <x-dynamic-component :component="'lucide-'.$other->icon" class="h-4 w-4 text-brand-500"/>{{ $other->title }}
                </a>
            @endforeach
        </div>
    </div>
</section>

@include('partials.cta-band')
@endsection
