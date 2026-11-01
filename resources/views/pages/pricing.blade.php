@extends('layouts.app', ['title' => __('site.pricing.title'), 'description' => __('site.pricing.intro')])

@push('jsonld')
    @include('partials.jsonld-faq', ['faqs' => $faqs])
    {{-- The plans as offers of a software product, so search engines can show the prices. --}}
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'SoftwareApplication',
        'name' => config('site.name'),
        'applicationCategory' => 'BusinessApplication',
        'operatingSystem' => 'Web',
        'offers' => $plans->filter->hasFixedPrice()->flatMap(fn ($plan) => [
            ['@type' => 'Offer', 'name' => $plan->name.' ('.__('site.pricing.billing_monthly').')', 'price' => $plan->monthly, 'priceCurrency' => 'USD', 'url' => url()->current()],
            ['@type' => 'Offer', 'name' => $plan->name.' ('.__('site.pricing.billing_yearly').')', 'price' => $plan->yearly, 'priceCurrency' => 'USD', 'url' => url()->current()],
        ])->values()->all(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')
@php
    use App\Support\Digits;
    $fixed = $plans->filter->hasFixedPrice();
    $bestSaving = $fixed->max(fn ($plan) => $plan->yearlySavingPercent()) ?? 0;
@endphp

@include('partials.page-head', ['heading' => __('site.pricing.title'), 'intro' => __('site.pricing.intro')])

<section class="section pt-10 sm:pt-14">
    <div class="container-page">
        @if ($plans->isEmpty())
            <div class="card text-center">
                <p class="text-slate-600">{{ __('site.pricing.empty') }}</p>
                <a href="{{ route('contact') }}" class="btn btn-primary mt-4">{{ __('site.nav.contact') }}</a>
            </div>
        @else
            {{-- The billing switch sets data-billing; the prices below follow it. --}}
            <div class="group/pricing" data-billing="yearly" id="pricing">
                @if ($fixed->isNotEmpty())
                    <div class="mb-12 flex flex-col items-center gap-3">
                        <div class="inline-flex rounded-full border border-slate-200 bg-surface p-1 shadow-sm" role="group" aria-label="{{ __('site.pricing.billing_yearly') }} / {{ __('site.pricing.billing_monthly') }}">
                            <button type="button" data-billing-set="monthly" aria-pressed="false"
                                    class="rounded-full px-5 py-2 text-sm font-bold text-slate-600 transition group-data-[billing=monthly]/pricing:bg-brand-500 group-data-[billing=monthly]/pricing:text-white">
                                {{ __('site.pricing.billing_monthly') }}
                            </button>
                            <button type="button" data-billing-set="yearly" aria-pressed="true"
                                    class="rounded-full px-5 py-2 text-sm font-bold text-slate-600 transition group-data-[billing=yearly]/pricing:bg-brand-500 group-data-[billing=yearly]/pricing:text-white">
                                {{ __('site.pricing.billing_yearly') }}
                            </button>
                        </div>
                        @if ($bestSaving > 0)
                            <p class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-bold text-green-700">
                                <x-lucide-sparkles class="h-3.5 w-3.5"/>{{ __('site.pricing.save_up_to', ['n' => Digits::localize($bestSaving)]) }}
                            </p>
                        @endif
                    </div>
                @endif

                <div class="grid items-stretch gap-6 lg:grid-cols-3">
                    @foreach ($plans as $plan)
                        @php($items = $plan->items())
                        <div class="relative flex flex-col rounded-3xl border bg-surface p-7 shadow-sm {{ $plan->is_featured ? 'border-brand-400 shadow-xl shadow-brand-500/15 ring-2 ring-brand-200 lg:-my-4 lg:py-11' : 'border-slate-200' }}">
                            @if ($plan->is_featured)
                                <span class="absolute inset-x-0 -top-3.5 mx-auto w-fit whitespace-nowrap rounded-full bg-gradient-to-r from-brand-500 to-brand-700 px-4 py-1 text-xs font-bold text-white shadow-md">{{ __('site.pricing.featured') }}</span>
                            @endif

                            <h2 class="text-2xl">{{ $plan->name }}</h2>
                            <p class="mt-1 min-h-[3rem] text-sm text-slate-600">{{ $plan->audience }}</p>

                            {{-- Price --}}
                            <div class="mt-6 border-b border-slate-100 pb-6">
                                @if ($plan->hasFixedPrice())
                                    <div class="flex items-baseline gap-2">
                                        <span dir="ltr" class="text-5xl font-extrabold tracking-tight text-slate-900">
                                            <span class="text-2xl font-bold text-brand-500">$</span><span class="hidden group-data-[billing=yearly]/pricing:inline">{{ Digits::localize($plan->yearly) }}</span><span class="group-data-[billing=yearly]/pricing:hidden">{{ Digits::localize($plan->monthly) }}</span>
                                        </span>
                                        <span class="text-sm text-slate-500">
                                            <span class="hidden group-data-[billing=yearly]/pricing:inline">{{ __('site.pricing.per_year') }}</span>
                                            <span class="group-data-[billing=yearly]/pricing:hidden">{{ __('site.pricing.per_month') }}</span>
                                        </span>
                                    </div>
                                    <p class="mt-2 min-h-[2.75rem] text-sm text-slate-500">
                                        <span class="hidden group-data-[billing=yearly]/pricing:block">
                                            {{ __('site.pricing.yearly_equiv', ['price' => Digits::number($plan->yearlyPerMonth())]) }}
                                            @if ($plan->yearlySaving() > 0)
                                                <span class="block font-bold text-green-700">{{ __('site.pricing.save', ['amount' => Digits::localize($plan->yearlySaving()), 'n' => Digits::localize($plan->yearlySavingPercent())]) }}</span>
                                            @endif
                                        </span>
                                        <span class="group-data-[billing=yearly]/pricing:hidden">{{ __('site.pricing.monthly_equiv', ['price' => Digits::localize($plan->yearly)]) }}</span>
                                    </p>
                                @else
                                    <p class="text-3xl font-extrabold text-slate-900">{{ $plan->price_label }}</p>
                                @endif
                            </div>

                            <a href="{{ route('demo', ['plan' => $plan->slug]) }}" class="btn {{ $plan->is_featured ? 'btn-primary shadow-lg shadow-brand-500/30' : 'btn-outline' }} mt-6 w-full py-3">{{ __('site.pricing.choose') }}</a>

                            {{-- What is included --}}
                            @if ($plan->includes)
                                <p class="mt-7 text-sm font-bold text-slate-900">{{ $plan->includes }}</p>
                            @else
                                <div class="mt-7"></div>
                            @endif
                            <ul class="mt-3 space-y-3 text-sm">
                                @foreach ($items as $item)
                                    <li class="flex items-start gap-2.5 {{ $item['soon'] ? 'text-slate-500' : 'text-slate-700' }}">
                                        @if ($item['soon'])
                                            <x-lucide-clock class="mt-0.5 h-4 w-4 shrink-0 text-amber-500"/>
                                        @else
                                            <x-lucide-circle-check class="mt-0.5 h-4 w-4 shrink-0 text-brand-500"/>
                                        @endif
                                        <span>
                                            {{ $item['text'] }}
                                            @if ($item['soon'])
                                                <span class="ms-1 inline-block rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-bold text-amber-800">{{ __('site.pricing.soon') }}</span>
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10 space-y-1 text-center text-sm text-slate-500">
                    <p>{{ __('site.pricing.usd_note') }}</p>
                    @if ($plans->contains(fn ($plan) => collect($plan->items())->contains('soon', true)))
                        <p>{{ __('site.pricing.soon_note') }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>

{{-- Add-ons --}}
@if ($addons->isNotEmpty())
<section class="section relative isolate bg-slate-50">
    <div aria-hidden="true" class="bg-dots fade-edges absolute inset-0 -z-10 opacity-50"></div>
    <div class="container-page">
        <div class="mb-10 max-w-2xl">
            <h2 class="text-3xl">{{ __('site.pricing.addons_title') }}</h2>
            <p class="mt-3 text-slate-600">{{ __('site.pricing.addons_intro') }}</p>
        </div>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($addons as $addon)
                <div class="card card-hover flex flex-col">
                    <span class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-md shadow-brand-500/25">
                        <x-dynamic-component :component="'lucide-'.$addon->icon" class="h-6 w-6"/>
                    </span>
                    <h3 class="text-lg">{{ $addon->name }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $addon->description }}</p>
                    <div class="mt-auto pt-5">
                        @if ($addon->price_label)<p class="text-xs font-bold text-slate-500">{{ $addon->price_label }}</p>@endif
                        <a href="{{ route('contact') }}" class="mt-1 inline-block text-sm font-bold text-brand-600 hover:underline">{{ __('site.pricing.addon_cta') }}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if ($faqs->isNotEmpty())
<section class="section">
    <div class="container-page max-w-3xl">
        <h2 class="mb-8 text-3xl">{{ __('site.pricing.faq_title') }}</h2>
        @include('partials.faq-list', ['faqs' => $faqs])
    </div>
</section>
@endif

@include('partials.cta-band', ['title' => __('site.pricing.help_choose_title'), 'text' => __('site.pricing.help_choose_text')])
@endsection
