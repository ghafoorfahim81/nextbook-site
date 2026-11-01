@extends('layouts.app', ['title' => __('site.industries.title')])

@section('content')
@include('partials.page-head', ['heading' => __('site.industries.title'), 'intro' => __('site.industries.intro')])

<section class="section">
    <div class="container-page grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($industries as $industry)
            @include('partials.card-industry', ['industry' => $industry])
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
