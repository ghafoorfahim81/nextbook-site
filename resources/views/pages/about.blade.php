@extends('layouts.app', ['title' => __('site.about.title')])

@section('content')
@include('partials.page-head', ['heading' => __('site.about.title')])

<section class="section">
    <div class="container-page max-w-3xl space-y-4 text-lg text-slate-700">
        @foreach (__('site.about.text') as $paragraph)
            <p>{{ $paragraph }}</p>
        @endforeach
    </div>
</section>

<section class="section bg-slate-50">
    <div class="container-page">
        <h2 class="mb-8 text-2xl">{{ __('site.about.principles_title') }}</h2>
        <div class="grid gap-5 md:grid-cols-3">
            @foreach (__('site.about.principles') as $item)
                <div class="card">
                    <x-dynamic-component :component="'lucide-'.$item['icon']" class="mb-3 h-8 w-8 text-brand-500"/>
                    <h3 class="text-lg">{{ $item['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $item['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

@include('partials.cta-band')
@endsection
