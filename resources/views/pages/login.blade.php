@extends('layouts.app', ['title' => __('site.login.title')])

@section('content')
<section class="section">
    <div class="container-page max-w-xl">
        <div class="card p-8">
            <img src="{{ asset('images/logo.png') }}" alt="" class="mx-auto mb-4 h-16 w-auto">
            <h1 class="text-center text-2xl">{{ __('site.login.title') }}</h1>
            <p class="mt-2 text-center text-slate-600">{{ __('site.login.intro') }}</p>

            <form method="POST" action="{{ route('login.go') }}" class="mt-6" id="workspace-form">
                @csrf
                <label class="label" for="workspace">{{ __('site.login.label') }}</label>
                <div class="flex items-stretch gap-2" dir="ltr">
                    <input class="input flex-1" id="workspace" name="workspace" value="{{ old('workspace') }}"
                           placeholder="{{ __('site.login.placeholder') }}" autocomplete="off" autocapitalize="none"
                           spellcheck="false" required autofocus>
                    <span dir="ltr" class="flex items-center rounded-xl bg-slate-100 px-3 text-sm text-slate-600">.{{ config('site.tenant_domain') }}</span>
                </div>
                @error('workspace')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                {{-- The address is Latin text inside a Dari sentence; <bdi> keeps its characters in order. --}}
                <p class="mt-2 text-xs text-slate-500">{!! __('site.login.hint', [
                    'address' => '<bdi dir="ltr" class="font-bold">noor.'.e(config('site.tenant_domain')).'</bdi>',
                    'name' => '<bdi dir="ltr" class="font-bold">noor</bdi>',
                ]) !!}</p>
                <button class="btn btn-primary mt-5 w-full">{{ __('site.login.go') }}</button>
            </form>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <div class="card p-5">
                <h2 class="text-base">{{ __('site.login.no_address') }}</h2>
                <p class="mt-1 text-sm text-slate-600">{{ __('site.login.no_address_text') }}</p>
                <a href="{{ route('contact') }}" class="mt-3 inline-block text-sm font-bold text-brand-600 hover:underline">{{ __('site.nav.contact') }}</a>
            </div>
            <div class="card p-5">
                <h2 class="text-base">{{ __('site.login.new_customer') }}</h2>
                <p class="mt-1 text-sm text-slate-600">{{ __('site.login.new_customer_text') }}</p>
                <a href="{{ route('demo') }}" class="mt-3 inline-block text-sm font-bold text-brand-600 hover:underline">{{ __('site.nav.demo') }}</a>
            </div>
        </div>
    </div>
</section>

<script>
    // Remember the company on this device so the next sign-in is one click.
    (function () {
        var input = document.getElementById('workspace');
        try {
            if (input && !input.value) { input.value = localStorage.getItem('nb_workspace') || ''; }
            document.getElementById('workspace-form').addEventListener('submit', function () {
                localStorage.setItem('nb_workspace', input.value.trim());
            });
        } catch (e) { /* storage blocked: the form still works */ }
    })();
</script>
@endsection
