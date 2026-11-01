@extends('layouts.app', ['title' => __('site.pricing.title')])

@section('content')
@include('partials.page-head', ['heading' => __('site.pricing.title'), 'intro' => __('site.pricing.intro')])

<section class="section">
    <div class="container-page">
        @if ($plans->isEmpty())
            <div class="card text-center">
                <p class="text-slate-600">{{ __('site.pricing.empty') }}</p>
                <a href="{{ route('contact') }}" class="btn btn-primary mt-4">{{ __('site.nav.contact') }}</a>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-{{ min($plans->count(), 3) }}">
                @foreach ($plans as $plan)
                    <div class="card relative flex flex-col {{ $plan->is_featured ? 'border-brand-400 ring-2 ring-brand-200' : '' }}">
                        @if ($plan->is_featured)
                            <span class="absolute -top-3 start-6 rounded-full bg-brand-500 px-3 py-1 text-xs font-bold text-white">{{ __('site.pricing.featured') }}</span>
                        @endif
                        <h2 class="text-xl">{{ $plan->name }}</h2>
                        <p class="mt-1 text-sm text-slate-600">{{ $plan->description }}</p>
                        <p class="mt-5 text-3xl font-extrabold text-slate-900">
                            {{ $plan->price_label }}
                            @if ($plan->period)<span class="text-sm font-normal text-slate-500">{{ $plan->period }}</span>@endif
                        </p>
                        <ul class="mt-5 flex-1 space-y-2 text-sm">
                            @foreach ((array) $plan->points as $point)
                                <li class="flex items-start gap-2"><x-lucide-check class="mt-0.5 h-4 w-4 shrink-0 text-brand-500"/>{{ $point }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('demo') }}" class="btn {{ $plan->is_featured ? 'btn-primary' : 'btn-outline' }} mt-6">{{ __('site.pricing.cta') }}</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

@if ($faqs->isNotEmpty())
<section class="section bg-slate-50">
    <div class="container-page max-w-3xl">
        <h2 class="mb-8 text-3xl">{{ __('site.pricing.faq_title') }}</h2>
        @include('partials.faq-list', ['faqs' => $faqs])
    </div>
</section>
@endif
@endsection
