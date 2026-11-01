@extends('layouts.app', ['title' => $feature->title, 'description' => $feature->summary])

@section('content')
@include('partials.page-head', [
    'heading' => $feature->title,
    'intro' => $feature->summary,
    'crumb' => ['url' => route('features.index'), 'label' => __('site.features.title')],
])

<section class="section">
    <div class="container-page grid gap-10 lg:grid-cols-3">
        <div class="lg:col-span-2">
            @if ($feature->status === 'soon')
                <div class="mb-6 rounded-xl border-s-4 border-accent-500 bg-amber-50 px-4 py-3 text-amber-900">
                    <b>{{ __('site.features.soon') }}.</b> {{ __('site.features.soon_note') }}
                </div>
            @endif

            <h2 class="mb-4 text-xl">{{ __('site.features.points') }}</h2>
            <ul class="grid gap-3">
                @foreach ((array) $feature->points as $point)
                    <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-4">
                        <x-lucide-check class="mt-1 h-5 w-5 shrink-0 text-brand-500"/>
                        <span>{{ $point }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <aside>
            <div class="card">
                <h3 class="mb-3 text-base">{{ __('site.features.other') }}</h3>
                <ul class="space-y-1">
                    @foreach ($others as $other)
                        <li>
                            <a href="{{ route('features.show', $other) }}" class="flex items-center gap-2 rounded-lg px-2 py-2 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-700">
                                <x-dynamic-component :component="'lucide-'.$other->icon" class="h-4 w-4 text-brand-500"/>
                                {{ $other->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>
    </div>
</section>

@include('partials.cta-band')
@endsection
