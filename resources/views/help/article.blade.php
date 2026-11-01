@extends('layouts.app', ['title' => $article->title, 'description' => $article->summary])

@php
    // The article is written in the visitor's language when a translation exists.
    $translated = filled($article->getTranslations('content')[app()->getLocale()] ?? null);
@endphp

@section('content')
@include('partials.page-head', [
    'heading' => $article->title,
    'intro' => $article->summary,
    'crumb' => ['url' => route('help.category', $category), 'label' => $category->title],
])

<section class="section">
    <div class="container-page grid gap-10 lg:grid-cols-4">
        <aside class="lg:order-last">
            <div class="card lg:sticky lg:top-24">
                <h3 class="mb-3 text-base">{{ __('site.help.in_category') }}</h3>
                <ul class="space-y-1">
                    @foreach ($siblings as $sibling)
                        <li>
                            <a href="{{ route('help.article', [$category, $sibling]) }}"
                               class="block rounded-lg px-2 py-2 text-sm {{ $sibling->id === $article->id ? 'bg-brand-50 font-bold text-brand-700' : 'text-slate-700 hover:bg-slate-50' }}">
                                {{ $sibling->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <article class="lg:col-span-3">
            @unless ($translated)
                <div class="mb-6 rounded-xl border-s-4 border-accent-500 bg-amber-50 px-4 py-3 text-sm text-amber-900">{{ __('site.help.not_translated') }}</div>
            @endunless

            <div class="prose-help">{!! $article->content !!}</div>

            <div class="mt-10 rounded-2xl border border-slate-200 bg-slate-50 p-5">
                @if (session('feedback'))
                    <p class="font-bold text-green-700">{{ __('site.help.thanks') }}</p>
                @else
                    <form method="POST" action="{{ route('help.feedback', $article) }}" class="flex flex-wrap items-center gap-3">
                        @csrf
                        <span class="font-bold text-slate-800">{{ __('site.help.was_helpful') }}</span>
                        <button name="helpful" value="yes" class="btn btn-outline">{{ __('site.help.yes') }}</button>
                        <button name="helpful" value="no" class="btn btn-outline">{{ __('site.help.no') }}</button>
                    </form>
                @endif
            </div>

            <div class="mt-6 card bg-brand-50">
                <h3 class="text-base">{{ __('site.help.still_stuck') }}</h3>
                <p class="mt-1 text-sm text-slate-600">{{ __('site.help.still_stuck_text') }}</p>
                <a href="{{ route('contact') }}" class="btn btn-primary mt-3">{{ __('site.nav.contact') }}</a>
            </div>
        </article>
    </div>
</section>
@endsection
