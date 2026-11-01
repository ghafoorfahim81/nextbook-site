<section class="section">
    <div class="container-page">
        <div class="relative isolate overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 via-brand-700 to-brand-900 px-6 py-14 text-center text-white sm:px-12">
            <div aria-hidden="true" class="bg-grid-light fade-edges absolute inset-0 -z-10"></div>
            <div aria-hidden="true" class="absolute -top-20 start-1/4 -z-10 h-64 w-64 rounded-full bg-brand-400/40 blur-3xl"></div>
            <div aria-hidden="true" class="absolute -bottom-24 end-1/4 -z-10 h-64 w-64 rounded-full bg-accent-500/25 blur-3xl"></div>
            <h2 class="text-2xl text-white sm:text-3xl">{{ $title ?? __('site.home.cta_title') }}</h2>
            <p class="mx-auto mt-3 max-w-2xl text-brand-100">{{ $text ?? __('site.home.cta_text') }}</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('demo') }}" class="btn btn-light px-6 py-3 text-base">{{ __('site.nav.demo') }}</a>
                <a href="{{ route('contact') }}" class="btn border border-white/40 px-6 py-3 text-base text-white hover:bg-white/10">{{ __('site.cta.contact') }}</a>
            </div>
        </div>
    </div>
</section>
