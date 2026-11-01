@extends('layouts.app', ['title' => __('site.search.title'), 'robots' => 'noindex, follow'])

@section('content')
<div class="relative isolate border-b border-slate-200 bg-gradient-to-b from-brand-50/70 to-surface">
    @include('partials.backdrop')
    <div class="container-page py-10 text-center sm:py-14">
        <h1 class="text-2xl sm:text-3xl">{{ __('site.search.title') }}</h1>
        <form method="GET" action="{{ route('search') }}" class="mx-auto mt-6 flex max-w-xl gap-2" role="search">
            <label for="page-q" class="sr-only">{{ __('site.search.title') }}</label>
            <input id="page-q" name="q" value="{{ $q }}" class="input flex-1 py-3" placeholder="{{ __('site.search.placeholder') }}" autofocus>
            <button class="btn btn-primary px-5"><x-lucide-search class="h-4 w-4"/>{{ __('site.search.go') }}</button>
        </form>
    </div>
</div>

<section class="section">
    <div class="container-page max-w-3xl">
        @if ($q === '')
            <p class="text-center text-slate-600">{{ __('site.search.hint') }}</p>
        @elseif ($tooShort)
            <p class="text-center text-slate-600">{{ __('site.search.too_short') }}</p>
        @elseif (empty($groups))
            <div class="card text-center text-slate-600">
                <p>{{ __('site.search.none', ['q' => $q]) }}</p>
                <a href="{{ route('contact') }}" class="btn btn-primary mt-4">{{ __('site.nav.contact') }}</a>
            </div>
        @else
            <p class="mb-6 text-sm text-slate-500">{{ __('site.search.results_for') }} «{{ $q }}»</p>
            <div class="space-y-10">
                @foreach ($groups as $group)
                    <div>
                        <h2 class="mb-3 text-sm font-bold uppercase tracking-wide text-brand-600">{{ $group['label'] }}</h2>
                        <div class="space-y-3">
                            @foreach ($group['items'] as $item)
                                <a href="{{ $item['url'] }}" class="card card-hover block p-4">
                                    @if ($item['meta'])<p class="text-xs font-bold text-brand-600">{{ $item['meta'] }}</p>@endif
                                    <h3 class="text-base">{{ $item['title'] }}</h3>
                                    @if ($item['snippet'])<p class="mt-1 text-sm text-slate-600">{{ $item['snippet'] }}</p>@endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
