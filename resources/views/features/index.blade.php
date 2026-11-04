@extends('layouts.app', ['title' => __('site.features.title')])

@section('content')
@include('partials.page-head', ['heading' => __('site.features.title'), 'intro' => __('site.features.intro')])

<section class="section">
    <div class="container-page note-grid grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($features as $feature)
            @include('partials.card-feature', ['feature' => $feature])
        @endforeach
    </div>
</section>

@include('partials.cta-band')
@endsection
