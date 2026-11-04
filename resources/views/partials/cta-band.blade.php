{{-- The last page: a sticky note and a clean page waiting to be written. --}}
<section class="section">
    <div class="container-page">
        <div class="relative isolate overflow-hidden rounded-[2rem] bg-gradient-to-br from-brand-600 via-brand-700 to-brand-900 px-6 py-14 text-white sm:px-14 sm:py-16">
            <div aria-hidden="true" class="bg-grid-light fade-edges absolute inset-0 -z-10"></div>
            <div aria-hidden="true" class="absolute -top-24 start-1/4 -z-10 h-64 w-64 rounded-full bg-brand-400/40 blur-3xl"></div>
            <div aria-hidden="true" class="absolute -bottom-24 end-1/4 -z-10 h-64 w-64 rounded-full bg-accent-500/25 blur-3xl"></div>
            <div class="grid items-center gap-10 md:grid-cols-[1.6fr_1fr]">
                <div>
                    <h2 class="text-3xl leading-snug text-white sm:text-4xl">{{ $title ?? __('site.story.cta.title') }}</h2>
                    <p class="mt-4 max-w-2xl text-lg text-brand-100">{{ $text ?? __('site.story.cta.text') }}</p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('demo') }}" class="btn btn-light px-7 py-3 text-base shadow-lg">{{ __('site.nav.demo') }}</a>
                        <a href="{{ route('contact') }}" class="btn border border-white/40 px-7 py-3 text-base text-white hover:bg-white/10">{{ __('site.cta.contact') }}</a>
                    </div>
                </div>
                <div aria-hidden="true" class="hidden md:block">
                    <div class="note tilt-r mx-auto max-w-[15rem] !bg-accent-400 !text-brand-900">
                        <p class="text-base font-bold">{{ __('site.story.footer_line') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
