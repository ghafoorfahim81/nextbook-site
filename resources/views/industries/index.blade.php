@extends('layouts.app', ['title' => __('site.industries.title')])

@section('content')
@include('partials.page-head', ['heading' => __('site.industries.title'), 'intro' => __('site.industries.intro')])

<section class="section">
    <div class="container-page grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($industries as $industry)
            <a href="{{ route('industries.show', $industry) }}" class="card group transition hover:border-brand-300 hover:shadow-md">
                <span class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                    <x-dynamic-component :component="'lucide-'.$industry->icon" class="h-6 w-6"/>
                </span>
                <h2 class="text-lg">{{ $industry->title }}</h2>
                <p class="mt-2 text-sm text-slate-600">{{ $industry->summary }}</p>
            </a>
        @endforeach
    </div>
    <div class="container-page mt-10">
        <div class="card bg-brand-50">
            <h3 class="text-lg">{{ __('site.industries.not_listed') }}</h3>
            <p class="mt-1 text-slate-600">{{ __('site.industries.not_listed_text') }}</p>
            <a href="{{ route('contact') }}" class="btn btn-primary mt-4">{{ __('site.nav.contact') }}</a>
        </div>
    </div>
</section>
@endsection
