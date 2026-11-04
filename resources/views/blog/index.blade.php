@extends('layouts.app', ['title' => __('site.blog.title'), 'description' => __('site.blog.intro')])

@section('content')
@php
    use App\Support\Digits;
    use App\Support\PostDate;
    $first = $posts->first();
    $rest = $posts->slice(1);
@endphp

@include('partials.page-head', ['heading' => __('site.blog.title'), 'intro' => __('site.blog.intro')])

<section class="section">
    <div class="container-page">
        @if ($posts->isEmpty())
            <div class="card text-center text-slate-600">{{ __('site.blog.empty') }}</div>
        @else
            {{-- The newest post, large. --}}
            @if ($posts->currentPage() === 1)
                <a href="{{ route('blog.show', $first) }}" class="card card-hover group relative isolate mb-12 block overflow-hidden !rounded-[2rem] bg-gradient-to-br from-brand-600 via-brand-700 to-brand-900 p-8 text-white sm:p-14">
                    <div aria-hidden="true" class="bg-grid-light fade-edges absolute inset-0 -z-10"></div>
                    <span aria-hidden="true" class="ribbon end-8"></span>
                    <span aria-hidden="true" class="absolute -bottom-6 start-6 -z-10 opacity-15">
                        <x-dynamic-component :component="'lucide-'.$first->icon" class="h-48 w-48 text-white sm:h-64 sm:w-64"/>
                    </span>
                    <div aria-hidden="true" class="absolute -top-16 end-10 -z-10 h-56 w-56 rounded-full bg-accent-500/25 blur-3xl"></div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-bold">{{ __('site.blog.latest') }}</span>
                    <h2 class="mt-4 max-w-3xl text-2xl text-white sm:text-4xl">{{ $first->title }}</h2>
                    <p class="mt-4 max-w-2xl text-brand-100 sm:text-lg">{{ $first->excerpt }}</p>
                    <p class="mt-6 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-brand-200">
                        <span>{{ PostDate::format($first->published_at) }}</span>
                        <span>{{ __('site.blog.minutes', ['n' => Digits::localize($first->readingMinutes())]) }}</span>
                    </p>
                    <span class="btn btn-light mt-8 px-7">{{ __('site.read_more') }}<x-lucide-arrow-right class="h-4 w-4 transition group-hover:-translate-x-1 ltr:rotate-180 ltr:group-hover:translate-x-1"/></span>
                </a>
            @endif

            <div class="note-grid grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts->currentPage() === 1 ? $rest : $posts as $post)
                    @include('blog.card', ['post' => $post])
                @endforeach
            </div>

            <div class="mt-10">{{ $posts->links() }}</div>
        @endif
    </div>
</section>

@include('partials.cta-band')
@endsection
