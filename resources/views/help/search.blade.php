@extends('layouts.app', ['title' => __('site.help.search')])

@section('content')
<div class="border-b border-slate-200 bg-gradient-to-b from-brand-50 to-white">
    <div class="container-page py-10 text-center">
        <h1 class="text-2xl sm:text-3xl">{{ __('site.help.search') }}</h1>
        @include('help.search-form', ['q' => $q])
    </div>
</div>

<section class="section">
    <div class="container-page max-w-3xl">
        @if ($q !== '')
            <p class="mb-6 text-sm text-slate-500">{{ __('site.help.results_for') }} «{{ $q }}»</p>
        @endif

        @forelse ($results as $article)
            <a href="{{ route('help.article', [$article->category, $article]) }}" class="card mb-3 block transition hover:border-brand-300">
                <p class="text-xs font-bold text-brand-600">{{ $article->category->title }}</p>
                <h2 class="mt-1 text-lg">{{ $article->title }}</h2>
                <p class="mt-1 text-sm text-slate-600">{{ $article->summary }}</p>
            </a>
        @empty
            @if ($q !== '')
                <div class="card text-center text-slate-600">{{ __('site.help.no_results') }}</div>
            @endif
        @endforelse
    </div>
</section>
@endsection
