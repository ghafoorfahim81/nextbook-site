@extends('layouts.app', ['title' => __('site.about.title')])

@section('content')
@php($a = __('site.story.about'))
@include('partials.page-head', ['heading' => $a['title'], 'intro' => $a['lead']])

{{-- The story as three pages of a notebook. --}}
<section class="section">
    <div class="container-page">
        <p class="eyebrow">{{ $a['eyebrow'] }}</p>
        <div class="mt-6 grid gap-6 md:grid-cols-3">
            @foreach ($a['chapters'] as $chapter)
                <div class="paper reveal px-6 pb-10 pt-6 ps-16 {{ $loop->index === 1 ? 'md:mt-8' : '' }} {{ $loop->last ? 'ring-2 ring-brand-300' : '' }}" style="--d: {{ $loop->index * 0.1 }}s">
                    <span aria-hidden="true" class="chapter-no absolute top-4 end-5 text-6xl">{{ \App\Support\Digits::localize($loop->iteration) }}</span>
                    <h2 class="mt-10 text-xl">{{ $chapter['title'] }}</h2>
                    <p class="mt-3 text-slate-600">{{ $chapter['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section bg-slate-50">
    <div class="container-page max-w-3xl space-y-4 text-lg text-slate-700">
        @foreach (__('site.about.text') as $paragraph)
            <p class="reveal">{{ $paragraph }}</p>
        @endforeach
        <p class="reveal note tilt-r !text-base font-bold">{{ $a['closing'] }}</p>
    </div>
</section>

<section class="section">
    <div class="container-page">
        <h2 class="reveal mb-8 text-2xl"><span class="mark">{{ __('site.about.principles_title') }}</span></h2>
        <div class="grid gap-5 md:grid-cols-3">
            @foreach (__('site.about.principles') as $item)
                <div class="card card-hover card-fold reveal" style="--d: {{ $loop->index * 0.08 }}s">
                    <span class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-md shadow-brand-500/25">
                        <x-dynamic-component :component="'lucide-'.$item['icon']" class="h-6 w-6"/>
                    </span>
                    <h3 class="text-lg">{{ $item['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $item['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

@include('partials.cta-band')
@endsection
