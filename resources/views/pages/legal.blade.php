@extends('layouts.app', ['title' => $heading])

@section('content')
@include('partials.page-head', [
    'heading' => $heading,
    'intro' => __('site.legal.effective').': '.__('site.legal.effective_date'),
])

<section class="section">
    <div class="container-page max-w-3xl">
        @unless ($translated)
            <div class="mb-6 rounded-xl border-s-4 border-accent-500 bg-amber-50 px-4 py-3 text-sm text-amber-900">{{ __('site.legal.not_translated') }}</div>
        @endunless

        {{-- The document keeps the direction of the language it is written in. --}}
        <div class="prose-help" dir="{{ $bodyDir }}">{!! $body !!}</div>
    </div>
</section>
@endsection
