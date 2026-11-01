@extends('layouts.app', ['title' => __('site.features.title')])

@section('content')
@include('partials.page-head', ['heading' => __('site.features.title'), 'intro' => __('site.features.intro')])

<section class="section">
    <div class="container-page grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($features as $feature)
            <a href="{{ route('features.show', $feature) }}" class="card group transition hover:border-brand-300 hover:shadow-md">
                <div class="mb-4 flex items-center justify-between">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                        <x-dynamic-component :component="'lucide-'.$feature->icon" class="h-6 w-6"/>
                    </span>
                    @if ($feature->status === 'soon')
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">{{ __('site.features.soon') }}</span>
                    @endif
                </div>
                <h2 class="text-lg">{{ $feature->title }}</h2>
                <p class="mt-2 text-sm text-slate-600">{{ $feature->summary }}</p>
            </a>
        @endforeach
    </div>
</section>

@include('partials.cta-band')
@endsection
