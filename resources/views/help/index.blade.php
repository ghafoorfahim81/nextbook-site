@extends('layouts.app', ['title' => __('site.help.title')])

@section('content')
@php($searchForm = view('help.search-form', ['q' => '', 'class' => 'w-full max-w-4xl'])->render())
@include('partials.page-head', ['heading' => __('site.help.title'), 'intro' => __('site.help.intro'), 'actions' => $searchForm])

<section class="section">
    <div class="container-page note-grid grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($categories as $category)
            <a href="{{ route('help.category', $category) }}" class="note-card group h-full">
                @include('partials.note-mark', ['icon' => $category->icon])
                <h2 class="text-lg">{{ $category->title }}</h2>
                <p class="text-sm text-slate-600">{{ $category->summary }}</p>
                <p class="row mt-8 text-xs font-bold text-brand-600">{{ $category->articles_count }} {{ __('site.help.articles') }}</p>
            </a>
        @endforeach
    </div>
</section>

@include('partials.cta-band', ['title' => __('site.help.still_stuck'), 'text' => __('site.help.still_stuck_text')])
@endsection
