@extends('layouts.app', ['title' => $industry->title, 'description' => $industry->summary])

@section('content')
@include('partials.page-head', [
    'heading' => $industry->title,
    'intro' => $industry->summary,
    'crumb' => ['url' => route('industries.index'), 'label' => __('site.industries.title')],
])

<section class="section">
    <div class="container-page grid gap-10 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <h2 class="mb-4 text-xl">{{ __('site.industries.how') }}</h2>
            <ul class="grid gap-3">
                @foreach ((array) $industry->points as $point)
                    <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-4">
                        <x-lucide-check class="mt-1 h-5 w-5 shrink-0 text-brand-500"/>
                        <span>{{ $point }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('demo', ['type' => $industry->business_type]) }}" class="btn btn-primary">{{ __('site.nav.demo') }}</a>
                <a href="{{ route('pricing') }}" class="btn btn-outline">{{ __('site.nav.pricing') }}</a>
            </div>
        </div>

        <aside>
            <div class="card">
                <h3 class="mb-3 text-base">{{ __('site.industries.other') }}</h3>
                <ul class="space-y-1">
                    @foreach ($others as $other)
                        <li>
                            <a href="{{ route('industries.show', $other) }}" class="flex items-center gap-2 rounded-lg px-2 py-2 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-700">
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
