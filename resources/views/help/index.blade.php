@extends('layouts.app', ['title' => __('site.help.title')])

@section('content')
<div class="border-b border-slate-200 bg-gradient-to-b from-brand-50 to-surface">
    <div class="container-page py-12 text-center sm:py-16">
        <h1 class="text-3xl sm:text-4xl">{{ __('site.help.title') }}</h1>
        <p class="mt-3 text-lg text-slate-600">{{ __('site.help.intro') }}</p>
        @include('help.search-form', ['q' => ''])
    </div>
</div>

<section class="section">
    <div class="container-page grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($categories as $category)
            <a href="{{ route('help.category', $category) }}" class="card group transition hover:border-brand-300 hover:shadow-md">
                <span class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                    <x-dynamic-component :component="'lucide-'.$category->icon" class="h-6 w-6"/>
                </span>
                <h2 class="text-lg">{{ $category->title }}</h2>
                <p class="mt-2 text-sm text-slate-600">{{ $category->summary }}</p>
                <p class="mt-3 text-xs font-bold text-brand-600">{{ $category->articles_count }} {{ __('site.help.articles') }}</p>
            </a>
        @endforeach
    </div>
</section>

@include('partials.cta-band', ['title' => __('site.help.still_stuck'), 'text' => __('site.help.still_stuck_text')])
@endsection
