@extends('layouts.app', ['title' => __('site.demo.title')])

@section('content')
@include('partials.page-head', ['heading' => __('site.demo.title'), 'intro' => __('site.demo.intro')])

<section class="section">
    <div class="container-page grid gap-10 lg:grid-cols-5">
        <div class="card lg:col-span-3">
            @if (session('sent'))
                <div class="rounded-xl bg-green-50 px-4 py-3 text-green-800">{{ __('site.demo.sent') }}</div>
            @else
                <form method="POST" action="{{ route('demo.store') }}" class="grid gap-4">
                    @csrf
                    <div class="hidden" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="label" for="name">{{ __('site.form.name') }}</label>
                            <input class="input" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label" for="phone">{{ __('site.form.phone') }}</label>
                            <input class="input" dir="ltr" id="phone" name="phone" value="{{ old('phone') }}" inputmode="tel" required>
                            @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="label" for="email">{{ __('site.form.email') }} <span class="text-slate-400">({{ __('site.form.optional') }})</span></label>
                            <input class="input" dir="ltr" id="email" name="email" type="email" value="{{ old('email') }}">
                            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label" for="company">{{ __('site.form.company') }} <span class="text-slate-400">({{ __('site.form.optional') }})</span></label>
                            <input class="input" id="company" name="company" value="{{ old('company') }}">
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="label" for="business_type">{{ __('site.form.business_type') }}</label>
                            <select class="input" id="business_type" name="business_type">
                                <option value="">{{ __('site.form.choose') }}</option>
                                @foreach (config('site.business_types') as $type)
                                    <option value="{{ $type }}" @selected(old('business_type', $selectedType) === $type)>{{ __('site.business_types.'.$type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="label" for="branches">{{ __('site.form.branches') }}</label>
                            <input class="input" dir="ltr" id="branches" name="branches" type="number" min="1" value="{{ old('branches') }}">
                        </div>
                    </div>
                    <div>
                        <label class="label" for="message">{{ __('site.form.message') }} <span class="text-slate-400">({{ __('site.form.optional') }})</span></label>
                        <textarea class="input" id="message" name="message" rows="4">{{ old('message', $prefill) }}</textarea>
                    </div>
                    <button class="btn btn-primary w-full sm:w-auto sm:justify-self-start">{{ __('site.demo.send') }}</button>
                </form>
            @endif
        </div>

        <div class="lg:col-span-2">
            <h2 class="mb-4 text-xl">{{ __('site.demo.steps_title') }}</h2>
            <ol class="space-y-4">
                @foreach (__('site.demo.steps') as $step)
                    <li class="flex items-start gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-500 text-sm font-bold text-white">{{ $loop->iteration }}</span>
                        <span class="pt-1">{{ $step }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>
@endsection
