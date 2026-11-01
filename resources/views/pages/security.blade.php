@extends('layouts.app', ['title' => __('site.security.title')])

@section('content')
@include('partials.page-head', ['heading' => __('site.security.title'), 'intro' => __('site.security.intro')])

<section class="section">
    <div class="container-page grid gap-5 md:grid-cols-2">
        @foreach (__('site.security.items') as $item)
            <div class="card flex gap-4">
                <x-dynamic-component :component="'lucide-'.$item['icon']" class="h-8 w-8 shrink-0 text-brand-500"/>
                <div>
                    <h2 class="text-lg">{{ $item['title'] }}</h2>
                    <p class="mt-1 text-sm text-slate-600">{{ $item['text'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

@include('partials.cta-band')
@endsection
