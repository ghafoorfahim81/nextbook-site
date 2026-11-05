@extends('layouts.app', ['title' => __('site.help.search')])

@section('content')
@php($searchForm = view('help.search-form', ['q' => $q, 'class' => 'w-full max-w-xl'])->render())
@include('partials.page-head', ['heading' => __('site.help.search'), 'actions' => $searchForm])

<section class="section">
    <div class="container-page">
        @if ($q !== '')
            <p class="mb-8 text-sm text-slate-500">{{ __('site.help.results_for') }} «{{ $q }}»</p>
        @endif

        @forelse ($results as $article)
            @if ($loop->first)<div class="note-grid grid gap-8 sm:grid-cols-2 lg:grid-cols-3">@endif
            <a href="{{ route('help.article', [$article->category, $article]) }}" class="note-card group h-full">
                <p class="row text-xs font-bold text-brand-600">{{ $article->category->title }}</p>
                <h2 class="text-lg">{{ $article->title }}</h2>
                <p class="text-sm text-slate-600">{{ $article->summary }}</p>
            </a>
            @if ($loop->last)</div>@endif
        @empty
            @if ($q !== '')
                <div class="card text-center text-slate-600">{{ __('site.help.no_results') }}</div>
            @endif
        @endforelse
    </div>
</section>
@endsection
