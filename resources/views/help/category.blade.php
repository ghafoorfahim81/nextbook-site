@extends('layouts.app', ['title' => $category->title, 'description' => $category->summary])

@section('content')
@include('partials.page-head', [
    'heading' => $category->title,
    'intro' => $category->summary,
    'crumb' => ['url' => route('help.index'), 'label' => __('site.help.title')],
])

<section class="section">
    <div class="container-page">
        @forelse ($category->articles as $article)
            @if ($loop->first)<div class="note-grid grid gap-8 sm:grid-cols-2 lg:grid-cols-3">@endif
            <a href="{{ route('help.article', [$category, $article]) }}" class="note-card group h-full">
                <h2 class="text-lg">{{ $article->title }}</h2>
                <p class="text-sm text-slate-600">{{ $article->summary }}</p>
            </a>
            @if ($loop->last)</div>@endif
        @empty
            <div class="card text-center text-slate-600">{{ __('site.help.empty') }}</div>
        @endforelse
    </div>
</section>
@endsection
