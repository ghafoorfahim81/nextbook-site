@extends('layouts.app', ['title' => __('site.faq.title')])

@section('content')
@include('partials.page-head', ['heading' => __('site.faq.title'), 'intro' => __('site.faq.intro')])

<section class="section">
    <div class="container-page max-w-3xl">
        @include('partials.faq-list', ['faqs' => $faqs, 'open' => true])
    </div>
</section>

@include('partials.cta-band', ['title' => __('site.help.still_stuck'), 'text' => __('site.help.still_stuck_text')])
@endsection
