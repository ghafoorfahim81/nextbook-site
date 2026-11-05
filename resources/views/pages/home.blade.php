@extends('layouts.app')

@push('jsonld')
    @include('partials.jsonld-faq', ['faqs' => $faqs])
@endpush

@section('content')
@php
    use App\Support\Digits;
    $s = __('site.story');
    $locale = app()->getLocale();
    $shot = file_exists(public_path("images/screenshots/dashboard-{$locale}.webp")) ? "dashboard-{$locale}" : 'dashboard-en';
    [$shotW, $shotH] = $locale === 'fa' ? [1085, 799] : [1089, 810];
    $money = $s['sale']['money'];
@endphp

{{-- Prologue: the old notebook and the new page, side by side. --}}
<section class="relative isolate overflow-hidden pb-20 sm:pb-28">
    @include('partials.backdrop')

    <div class="container-page grid items-center gap-14 pt-14 sm:pt-20 lg:grid-cols-[1.05fr_1fr]">
        <div>
            <span class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-surface/80 px-4 py-1.5 text-sm font-bold text-brand-700 shadow-sm backdrop-blur">
                <span class="h-2 w-2 rounded-full bg-accent-500"></span>{{ $s['hero']['eyebrow'] }}
            </span>
            <h1 class="mt-6 text-4xl leading-[1.35] sm:text-5xl lg:text-[3.4rem]">
                {{ $s['hero']['title_a'] }}
                <span class="mt-2 block"><span class="mark text-brand-700">{{ $s['hero']['title_b'] }}</span> {{ $s['hero']['title_c'] }}</span>
            </h1>
            <p class="mt-6 max-w-xl text-lg text-slate-600">{{ $s['hero']['text'] }}</p>

            <div class="mt-9 flex flex-wrap gap-3">
                <a href="{{ route('demo') }}" class="btn btn-primary px-8 py-3.5 text-base shadow-lg shadow-brand-500/30">{{ $s['hero']['cta_demo'] }}</a>
                <a href="#story" class="btn btn-outline px-8 py-3.5 text-base">{{ $s['hero']['cta_story'] }}</a>
            </div>
            <ul class="mt-8 flex flex-wrap gap-2 text-sm font-medium text-slate-600">
                @foreach ($s['hero']['pills'] as $pill)
                    <li class="rounded-full border border-slate-200 bg-surface px-3.5 py-1">{{ $pill }}</li>
                @endforeach
            </ul>
        </div>

        {{-- The notebook page with its crossed-out numbers, and the clean page that replaces it. --}}
        <div class="relative mx-auto w-full max-w-xl pb-6" aria-hidden="true">
            <div class="paper tilt-l relative z-0 w-[78%] px-6 pb-8 pt-6 ps-16">
                <p class="mb-3 text-xs font-bold text-slate-400">{{ $s['hero']['old_title'] }}</p>
                <ul class="text-[15px] font-medium text-slate-700">
                    @foreach ($s['hero']['old'] as $line)
                        <li class="h-8 leading-8 {{ $loop->index % 2 === 0 ? 'strike' : '' }}">{{ $line }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="card relative z-10 -mt-10 ms-auto w-[78%] bg-surface p-5 shadow-2xl shadow-brand-500/20 ring-4 ring-brand-100/60 sm:-mt-12">
                <div class="mb-4 flex items-center justify-between">
                    <span class="text-sm font-bold text-brand-700">{{ $s['hero']['new_title'] }}</span>
                    <span class="stamp text-xs">{{ $s['hero']['stamp'] }}</span>
                </div>
                <dl class="divide-y divide-slate-100">
                    @foreach ($s['hero']['new'] as $row)
                        <div class="flex items-center justify-between gap-3 py-2.5">
                            <dt class="text-sm text-slate-500">{{ $row['label'] }}</dt>
                            <dd class="text-sm font-bold text-slate-900">{{ $row['value'] }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div class="note tilt-r absolute -top-3 end-0 hidden sm:block">{{ $s['hero']['sample'] }}</div>
        </div>
    </div>
</section>

{{-- Chapter 1: a day in the shop, before and after. --}}
<section id="story" class="section relative isolate scroll-mt-16 bg-slate-50">
    <div aria-hidden="true" class="bg-dots fade-edges absolute inset-0 -z-10 opacity-50"></div>
    <div class="container-page">
        @include('partials.chapter', ['no' => 1, 'eyebrow' => $s['day']['eyebrow'], 'title' => $s['day']['title'], 'text' => $s['day']['text']])

        <div class="day" data-day data-mode="after">
            <div class="reveal mb-10 inline-flex rounded-full border border-brand-200 bg-surface p-1 shadow-sm" role="group">
                <button type="button" data-day-set="before" aria-pressed="false" class="rounded-full px-5 py-2 text-sm font-bold text-slate-600 transition aria-pressed:bg-brand-900 aria-pressed:text-white">{{ $s['day']['before'] }}</button>
                <button type="button" data-day-set="after" aria-pressed="true" class="rounded-full px-5 py-2 text-sm font-bold text-slate-600 transition aria-pressed:bg-brand-500 aria-pressed:text-white">{{ $s['day']['after'] }}</button>
            </div>

            <ol class="relative space-y-5 ps-10 sm:ps-14">
                <span aria-hidden="true" class="day-line absolute inset-y-3 start-4 w-0.5 rounded-full sm:start-6"></span>
                @foreach ($s['day']['moments'] as $moment)
                    <li class="reveal relative" style="--d: {{ $loop->index * 0.05 }}s">
                        <span aria-hidden="true" class="absolute top-5 -start-[2.125rem] h-5 w-5 rounded-full border-4 border-surface bg-brand-500 ring-2 ring-brand-300 sm:-start-[2.625rem]"></span>
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-6">
                            <span class="inline-flex w-fit shrink-0 rounded-full bg-brand-500 px-4 py-1.5 text-sm font-bold text-white sm:w-24 sm:justify-center">{{ $moment['time'] }}</span>
                            <div data-when="before" class="note tilt-l w-full max-w-xl">
                                <p class="strike">{{ $moment['before'] }}</p>
                            </div>
                            <div data-when="after" class="card flex w-full max-w-xl items-start gap-3 border-brand-200 bg-brand-50/60 p-4">
                                <x-lucide-circle-check class="mt-0.5 h-5 w-5 shrink-0 text-brand-500"/>
                                <p class="font-medium text-slate-800">{{ $moment['after'] }}</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>

{{-- Chapter 2: record one sale and watch the book write itself. --}}
<section class="section">
    <div class="container-page">
        @include('partials.chapter', ['no' => 2, 'eyebrow' => $s['sale']['eyebrow'], 'title' => $s['sale']['title'], 'text' => $s['sale']['text']])

        <div id="sale-demo" class="grid gap-6 lg:grid-cols-[1fr_1.1fr]"
             data-locale="{{ $locale === 'fa' ? 'fa' : 'en-US' }}" data-price="1850" data-cost="1600" data-stock="140"
             data-money="{{ $money }}" data-debt-zero="{{ $s['sale']['debt_zero'] }}" data-debt-has="{{ $s['sale']['debt_has'] }}"
             data-cash="{{ $s['sale']['ledger_cash'] }}" data-receivable="{{ $s['sale']['ledger_receivable'] }}" data-sales="{{ $s['sale']['ledger_sales'] }}"
             data-stock-unit="{{ $s['sale']['stock_unit'] }}" data-again="{{ $s['sale']['again'] }}" data-submit-label="{{ $s['sale']['submit'] }}"
             data-waiting="{{ $s['sale']['waiting'] }}">

            {{-- The bill --}}
            <div class="paper reveal px-6 pb-8 pt-6 ps-16 sm:ps-20">
                <div class="mb-5 flex items-center justify-between gap-3">
                    <h3 class="text-lg">{{ $s['sale']['bill'] }} <span class="text-slate-400">· {{ Digits::localize(1043) }}</span></h3>
                    <span data-stamp class="stamp hidden text-sm">{{ $s['hero']['stamp'] }}</span>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400">{{ $s['sale']['customer'] }}</p>
                        <p class="font-bold text-slate-900">{{ $s['sale']['customer_name'] }}</p>
                    </div>
                    <div>
                        <p class="font-bold text-slate-900">{{ $s['sale']['item'] }}</p>
                        <p class="text-sm text-slate-500">{{ $s['sale']['item_sub'] }}</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-8 gap-y-4">
                        <div>
                            <p class="mb-1 text-xs font-bold text-slate-400">{{ $s['sale']['qty'] }}</p>
                            <div class="inline-flex items-center rounded-full border border-brand-200 bg-surface">
                                <button type="button" data-qty="-5" class="h-10 w-10 text-xl font-bold text-brand-600 hover:text-brand-800" aria-label="-5">−</button>
                                <output data-qty-out class="min-w-12 text-center text-lg font-bold text-slate-900">{{ Digits::localize(20) }}</output>
                                <button type="button" data-qty="5" class="h-10 w-10 text-xl font-bold text-brand-600 hover:text-brand-800" aria-label="+5">+</button>
                            </div>
                        </div>
                        <div>
                            <p class="mb-1 text-xs font-bold text-slate-400">{{ $s['sale']['pay'] }}</p>
                            <div class="inline-flex rounded-full border border-brand-200 bg-surface p-0.5" role="group">
                                <button type="button" data-pay="cash" aria-pressed="true" class="rounded-full px-4 py-2 text-sm font-bold text-slate-600 aria-pressed:bg-brand-500 aria-pressed:text-white">{{ $s['sale']['pay_cash'] }}</button>
                                <button type="button" data-pay="credit" aria-pressed="false" class="rounded-full px-4 py-2 text-sm font-bold text-slate-600 aria-pressed:bg-brand-500 aria-pressed:text-white">{{ $s['sale']['pay_credit'] }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-end justify-between gap-3 border-t border-dashed border-brand-300 pt-4">
                        <span class="text-slate-500">{{ $s['sale']['total'] }}</span>
                        <span class="text-2xl font-bold text-slate-900"><span data-total>{{ Digits::localize('37,000') }}</span> <span class="text-base font-medium text-slate-500">{{ $money }}</span></span>
                    </div>

                    <button type="button" data-submit class="btn btn-primary w-full py-3.5 text-base">{{ $s['sale']['submit'] }}</button>
                    <p class="text-xs text-slate-500">{{ $s['sale']['note'] }}</p>
                </div>
            </div>

            {{-- What the book wrote on its own --}}
            <div class="grid content-start gap-4 sm:grid-cols-2">
                @foreach ([
                    ['stock', 'boxes', $s['sale']['stock_title']],
                    ['debt', 'handshake', $s['sale']['debt_title']],
                    ['ledger', 'book-open', $s['sale']['ledger_title']],
                    ['profit', 'trending-up', $s['sale']['profit_title']],
                ] as [$key, $icon, $label])
                    <div class="card reveal {{ $key === 'ledger' ? 'sm:col-span-2' : '' }}" style="--d: {{ $loop->index * 0.08 }}s">
                        <div class="mb-3 flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-2 text-sm font-bold text-slate-900">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-brand-50 text-brand-600"><x-dynamic-component :component="'lucide-'.$icon" class="h-4 w-4"/></span>{{ $label }}
                            </span>
                            <span class="rounded-full bg-brand-50 px-2.5 py-0.5 text-[11px] font-bold text-brand-600">{{ $s['sale']['auto'] }}</span>
                        </div>
                        <div data-out="{{ $key }}" class="min-h-14 text-slate-400">{{ $s['sale']['waiting'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Chapter 3: the six pages of the book. --}}
<section class="section relative isolate">
    <div aria-hidden="true" class="bg-dots fade-edges absolute inset-x-0 top-0 -z-10 h-96 opacity-60"></div>
    <div class="container-page">
        @include('partials.chapter', ['no' => 3, 'eyebrow' => $s['features']['eyebrow'], 'title' => $s['features']['title'], 'text' => $s['features']['text']])
        <div class="note-grid grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($features->take(6) as $feature)
                <div class="reveal" style="--d: {{ $loop->index * 0.06 }}s">@include('partials.card-feature', ['feature' => $feature])</div>
            @endforeach
        </div>
        <div class="mt-10"><a href="{{ route('features.index') }}" class="btn btn-outline">{{ $s['features']['all'] }}</a></div>

        {{-- The details of real trade that make a notebook hard to replace. --}}
        <div class="mt-24">
            @include('partials.chapter', ['eyebrow' => $s['details']['eyebrow'], 'title' => $s['details']['title'], 'text' => $s['details']['text']])
            <div class="note-grid grid gap-8 lg:grid-cols-6">
                @foreach ($s['details']['items'] as $item)
                    <div class="reveal {{ $loop->index < 3 ? 'lg:col-span-2' : 'lg:col-span-3' }}" style="--d: {{ $loop->index * 0.06 }}s">
                        <div class="note-card h-full">
                            @include('partials.note-mark', ['icon' => $item['icon']])
                            <h3 class="text-lg">{{ $item['title'] }}</h3>
                            <p class="text-sm text-slate-600">{{ $item['text'] }}</p>
                            <p class="row mt-8"><span class="inline-flex h-8 items-center rounded-full border border-dashed border-brand-400 px-3 text-xs font-bold leading-none text-brand-700">{{ $item['example'] }}</span></p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Chapter 4: every trader keeps a different book. --}}
<section class="section relative isolate bg-slate-50">
    <div aria-hidden="true" class="bg-grid fade-center absolute inset-0 -z-10"></div>
    <div class="container-page">
        @include('partials.chapter', ['no' => 4, 'eyebrow' => $s['industries']['eyebrow'], 'title' => $s['industries']['title'], 'text' => $s['industries']['text']])
        <div class="flex flex-wrap gap-3">
            @foreach ($industries->take(12) as $industry)
                <a href="{{ route('industries.show', $industry) }}" class="reveal card card-hover flex items-center gap-3 !rounded-full py-2.5 pe-6 ps-3" style="--d: {{ $loop->index * 0.04 }}s">
                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                        <x-dynamic-component :component="'lucide-'.$industry->icon" class="h-5 w-5"/>
                    </span>
                    <span class="text-sm font-bold text-slate-800">{{ $industry->title }}</span>
                </a>
            @endforeach
        </div>
        <div class="mt-10"><a href="{{ route('industries.index') }}" class="btn btn-outline">{{ $s['industries']['all'] }}</a></div>
    </div>
</section>

{{-- The questions asked every night. --}}
<section class="section">
    <div class="container-page">
        @include('partials.chapter', ['eyebrow' => $s['ask']['eyebrow'], 'title' => $s['ask']['title'], 'text' => $s['ask']['text']])

        <div id="ask" class="grid gap-6 lg:grid-cols-[1fr_1.2fr]">
            <div class="reveal">
                <p class="mb-3 text-sm font-bold text-slate-500">{{ $s['ask']['pick'] }}</p>
                <div class="flex flex-col gap-2" role="tablist">
                    @foreach ($s['ask']['items'] as $i => $item)
                        <button type="button" role="tab" data-ask="{{ $i }}" aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                                class="rounded-2xl border border-slate-200 bg-surface px-5 py-4 text-start font-bold text-slate-800 transition hover:border-brand-300 aria-selected:border-brand-500 aria-selected:bg-brand-500 aria-selected:text-white aria-selected:shadow-lg aria-selected:shadow-brand-500/25">
                            {{ $item['q'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="reveal paper min-h-72 px-6 pb-8 pt-6 ps-16 sm:ps-20" style="--d: .1s">
                @foreach ($s['ask']['items'] as $i => $item)
                    <div data-ask-panel="{{ $i }}" role="tabpanel" @if ($i > 0) hidden @endif>
                        <p class="text-sm font-bold text-brand-600">{{ $item['tag'] }}</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ $item['value'] }}</p>
                        <p class="mt-1 text-slate-600">{{ $item['sub'] }}</p>
                        <dl class="mt-6 text-slate-700">
                            @foreach ($item['rows'] as [$k, $v])
                                <div class="flex h-8 items-center justify-between gap-4 leading-8"><dt>{{ $k }}</dt><dd class="font-bold text-slate-900">{{ $v }}</dd></div>
                            @endforeach
                        </dl>
                        <p class="mt-6 text-xs text-slate-400">{{ $s['ask']['sample'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Chapter 5: the book that does not get lost. --}}
<section class="section relative isolate bg-slate-50">
    <div aria-hidden="true" class="bg-dots fade-edges absolute inset-0 -z-10 opacity-50"></div>
    <div class="container-page">
        @include('partials.chapter', ['no' => 5, 'eyebrow' => $s['trust']['eyebrow'], 'title' => $s['trust']['title']])
        <div class="grid gap-5 md:grid-cols-3">
            @foreach (__('site.home.trust') as $item)
                <div class="card card-hover reveal" style="--d: {{ $loop->index * 0.08 }}s">
                    <span class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-md shadow-brand-500/25">
                        <x-dynamic-component :component="'lucide-'.$item['icon']" class="h-6 w-6"/>
                    </span>
                    <h3 class="text-lg">{{ $item['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $item['text'] }}</p>
                </div>
            @endforeach
        </div>

        <dl class="reveal mt-8 grid grid-cols-2 gap-px overflow-hidden rounded-2xl border border-slate-200 bg-slate-200 shadow-sm md:grid-cols-4">
            @foreach ([
                ['features', $stats['features']], ['industries', $stats['industries']],
                ['guides', $stats['guides']], ['languages', 3],
            ] as [$key, $value])
                <div class="bg-surface px-6 py-6 text-center">
                    <dd class="text-3xl font-bold text-brand-600">{{ Digits::localize($value) }}</dd>
                    <dt class="mt-1 text-sm text-slate-600">{{ __('site.stats.'.$key) }}</dt>
                </div>
            @endforeach
        </dl>
        <div class="mt-8"><a href="{{ route('security') }}" class="font-bold text-brand-600 hover:underline">{{ __('site.footer.security') }} ←</a></div>
    </div>
</section>

{{-- The product itself, in a browser window. --}}
<section class="section">
    <div class="container-page max-w-5xl">
        <div class="relative">
            <div aria-hidden="true" class="absolute inset-x-10 -bottom-6 top-1/3 -z-10 rounded-full bg-brand-400/30 blur-3xl"></div>
            <div class="browser browser-tilt">
                <div class="browser-bar" dir="ltr">
                    <span class="h-3 w-3 rounded-full bg-red-400"></span>
                    <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                    <span class="h-3 w-3 rounded-full bg-green-400"></span>
                    <span class="mx-auto flex items-center gap-1.5 rounded-md bg-surface px-3 py-1 text-xs text-slate-500 ring-1 ring-slate-200">
                        <x-lucide-shield-check class="h-3.5 w-3.5 text-green-600"/>your-company.nextbook.af
                    </span>
                    <span class="w-12"></span>
                </div>
                <img src="{{ asset("images/screenshots/{$shot}.webp") }}" width="{{ $shotW }}" height="{{ $shotH }}"
                     alt="{{ __('site.hero.shot_alt') }}" loading="lazy" class="block w-full">
            </div>
        </div>
    </div>
</section>

{{-- Chapter 6: the first page. --}}
<section class="section">
    <div class="container-page">
        @include('partials.chapter', ['no' => 6, 'eyebrow' => $s['start']['eyebrow'], 'title' => $s['start']['title']])
        <ol class="grid gap-6 md:grid-cols-3">
            @foreach (__('site.home.start') as $step)
                <li class="paper reveal px-6 pb-8 pt-6 ps-16" style="--d: {{ $loop->index * 0.1 }}s">
                    <span aria-hidden="true" class="chapter-no absolute top-4 end-5 text-6xl">{{ Digits::localize($loop->iteration) }}</span>
                    <h3 class="mt-10 text-lg">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>
        <div class="mt-8"><a href="{{ route('help.category', ['category' => 'getting-started']) }}" class="font-bold text-brand-600 hover:underline">{{ __('site.home.start_guide') }} ←</a></div>
    </div>
</section>

@if ($faqs->isNotEmpty())
<section class="section bg-slate-50">
    <div class="container-page max-w-3xl">
        <h2 class="reveal mb-8 text-3xl"><span class="mark">{{ $s['faq_title'] }}</span></h2>
        @include('partials.faq-list', ['faqs' => $faqs, 'open' => true])
    </div>
</section>
@endif

@include('partials.cta-band')
@endsection
