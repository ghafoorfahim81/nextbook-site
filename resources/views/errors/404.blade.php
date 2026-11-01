@extends('layouts.app', ['title' => __('site.not_found')])

@section('content')
<section class="section">
    <div class="container-page max-w-xl text-center">
        <p class="text-6xl font-extrabold text-brand-500">404</p>
        <h1 class="mt-4 text-2xl">{{ __('site.not_found') }}</h1>
        <p class="mt-2 text-slate-600">{{ __('site.not_found_text') }}</p>
        <a href="{{ route('home') }}" class="btn btn-primary mt-6">{{ __('site.go_home') }}</a>
    </div>
</section>
@endsection
