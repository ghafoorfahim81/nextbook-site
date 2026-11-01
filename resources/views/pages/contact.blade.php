@extends('layouts.app', ['title' => __('site.contact.title')])

@section('content')
@include('partials.page-head', ['heading' => __('site.contact.title'), 'intro' => __('site.contact.intro')])

<section class="section">
    <div class="container-page grid gap-10 lg:grid-cols-5">
        <div class="space-y-4 lg:col-span-2">
            <div class="card">
                <h2 class="mb-3 flex items-center gap-2 text-base"><x-lucide-phone class="h-5 w-5 text-brand-500"/>{{ __('site.contact.phone') }}</h2>
                @foreach (config('site.phones') as $phone)
                    <a dir="ltr" href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="block py-1 font-bold text-slate-800 hover:text-brand-600" style="text-align:start">{{ $phone }}</a>
                @endforeach
            </div>
            <div class="card">
                <h2 class="mb-3 flex items-center gap-2 text-base"><x-lucide-mail class="h-5 w-5 text-brand-500"/>{{ __('site.contact.email') }}</h2>
                <p class="text-xs text-slate-500">{{ __('site.contact.general') }}</p>
                <a dir="ltr" href="mailto:{{ config('site.emails.general') }}" class="mb-2 block font-bold text-slate-800 hover:text-brand-600" style="text-align:start">{{ config('site.emails.general') }}</a>
                <p class="text-xs text-slate-500">{{ __('site.contact.sales') }}</p>
                <a dir="ltr" href="mailto:{{ config('site.emails.sales') }}" class="block font-bold text-slate-800 hover:text-brand-600" style="text-align:start">{{ config('site.emails.sales') }}</a>
            </div>
            @if (config('site.whatsapp'))
                <a href="https://wa.me/{{ config('site.whatsapp') }}" target="_blank" rel="noopener" class="btn w-full bg-green-500 text-white hover:bg-green-600">
                    <x-lucide-message-circle class="h-5 w-5"/>{{ __('site.contact.whatsapp') }}
                </a>
            @endif
        </div>

        <div class="card lg:col-span-3">
            <h2 class="mb-4 text-xl">{{ __('site.contact.write_us') }}</h2>
            @if (session('sent'))
                <div class="rounded-xl bg-green-50 px-4 py-3 text-green-800">{{ __('site.contact.sent') }}</div>
            @else
                <form method="POST" action="{{ route('contact.store') }}" class="grid gap-4">
                    @csrf
                    <div class="hidden" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
                    <div>
                        <label class="label" for="name">{{ __('site.form.name') }}</label>
                        <input class="input" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="label" for="phone">{{ __('site.form.phone') }}</label>
                            <input class="input" dir="ltr" id="phone" name="phone" value="{{ old('phone') }}" inputmode="tel">
                            @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label" for="email">{{ __('site.form.email') }}</label>
                            <input class="input" dir="ltr" id="email" name="email" type="email" value="{{ old('email') }}">
                            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label class="label" for="subject">{{ __('site.form.subject') }} <span class="text-slate-400">({{ __('site.form.optional') }})</span></label>
                        <input class="input" id="subject" name="subject" value="{{ old('subject') }}">
                    </div>
                    <div>
                        <label class="label" for="message">{{ __('site.form.message') }}</label>
                        <textarea class="input" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                        @error('message')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button class="btn btn-primary w-full sm:w-auto sm:justify-self-start">{{ __('site.contact.send') }}</button>
                </form>
            @endif
        </div>
    </div>
</section>
@endsection
