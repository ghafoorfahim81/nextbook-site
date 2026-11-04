@extends('layouts.app', ['title' => $post->title, 'description' => $post->excerpt, 'ogType' => 'article'])

@push('jsonld')
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $post->title,
        'description' => $post->excerpt,
        'datePublished' => $post->published_at->toIso8601String(),
        'dateModified' => $post->updated_at->toIso8601String(),
        'inLanguage' => app()->getLocale(),
        'image' => asset('images/og.png'),
        'mainEntityOfPage' => url()->current(),
        'author' => ['@type' => 'Organization', 'name' => config('site.name')],
        'publisher' => ['@type' => 'Organization', 'name' => config('site.name'), 'logo' => ['@type' => 'ImageObject', 'url' => asset('icon-512.png')]],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')
@php
    use App\Support\Digits;
    use App\Support\PostDate;
    $translated = filled($post->getTranslations('content')[app()->getLocale()] ?? null);
@endphp

@include('partials.page-head', [
    'heading' => $post->title,
    'intro' => $post->excerpt,
    'crumb' => ['url' => route('blog.index'), 'label' => __('site.blog.title')],
])

<section class="section">
    <div class="container-page max-w-3xl">
        <p class="mb-8 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate-500">
            <span class="inline-flex items-center gap-1.5"><x-lucide-calendar-clock class="h-4 w-4"/>{{ PostDate::format($post->published_at) }}</span>
            <span class="inline-flex items-center gap-1.5"><x-lucide-clock class="h-4 w-4"/>{{ __('site.blog.minutes', ['n' => Digits::localize($post->readingMinutes())]) }}</span>
        </p>

        @unless ($translated)
            <div class="mb-6 rounded-xl border-s-4 border-accent-500 bg-amber-50 px-4 py-3 text-sm text-amber-900">{{ __('site.help.not_translated') }}</div>
        @endunless

        <article class="prose-help text-lg">{!! $post->content !!}</article>
    </div>
</section>

@if ($related->isNotEmpty())
<section class="section relative isolate bg-slate-50">
    <div aria-hidden="true" class="bg-dots fade-edges absolute inset-0 -z-10 opacity-50"></div>
    <div class="container-page">
        <h2 class="mb-8 text-2xl">{{ __('site.blog.more') }}</h2>
        <div class="note-grid grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($related as $other)
                @include('blog.card', ['post' => $other])
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.cta-band')
@endsection
