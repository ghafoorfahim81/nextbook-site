@extends('layouts.app', ['title' => $category->title, 'description' => $category->summary])

@section('content')
@include('partials.page-head', [
    'heading' => $category->title,
    'intro' => $category->summary,
    'crumb' => ['url' => route('help.index'), 'label' => __('site.help.title')],
])

<section class="section">
    <div class="container-page max-w-3xl">
        @forelse ($category->articles as $article)
            <a href="{{ route('help.article', [$category, $article]) }}" class="card mb-3 block transition hover:border-brand-300">
                <h2 class="text-lg">{{ $article->title }}</h2>
                <p class="mt-1 text-sm text-slate-600">{{ $article->summary }}</p>
            </a>
        @empty
            <div class="card text-center text-slate-600">{{ __('site.help.empty') }}</div>
        @endforelse
    </div>
</section>
@endsection
