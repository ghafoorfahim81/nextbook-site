@php($appUrl = rtrim(config('site.app_url'), '/'))
<section class="section">
    <div class="container-page">
        <div class="rounded-3xl bg-gradient-to-br from-brand-600 to-brand-800 px-6 py-12 text-center text-white sm:px-12">
            <h2 class="text-2xl text-white sm:text-3xl">{{ $title ?? __('site.home.cta_title') }}</h2>
            <p class="mx-auto mt-3 max-w-2xl text-brand-100">{{ $text ?? __('site.home.cta_text') }}</p>
            <div class="mt-7 flex flex-wrap justify-center gap-3">
                <a href="{{ route('demo') }}" class="btn btn-light">{{ __('site.nav.demo') }}</a>
                <a href="{{ $appUrl }}/register" class="btn border border-white/40 text-white hover:bg-white/10">{{ __('site.cta.trial') }}</a>
            </div>
        </div>
    </div>
</section>
