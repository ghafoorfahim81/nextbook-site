{{-- The top of an inner page: a fresh ruled page with a bookmark ribbon. --}}
<div class="page-top">
    <span aria-hidden="true" class="ribbon start-6 sm:start-[max(1.5rem,calc((100%-72rem)/2+1.5rem))]"></span>
    <div aria-hidden="true" class="pointer-events-none absolute -top-24 end-[8%] -z-10 h-72 w-72 rounded-full bg-accent-400/20 blur-3xl"></div>
    <div class="container-page py-14 sm:py-20">
        <div class="ps-10 sm:ps-12">
            @isset($crumb)
                <a href="{{ $crumb['url'] }}" class="mb-4 inline-flex items-center gap-1 text-sm font-medium text-brand-600 hover:underline">
                    <x-lucide-arrow-right class="h-4 w-4 rtl:rotate-0 ltr:rotate-180"/>{{ $crumb['label'] }}
                </a>
            @endisset
            <div class="flex items-start gap-5">
                @isset($icon)
                    <span class="hidden h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-lg shadow-brand-500/30 sm:inline-flex">
                        <x-dynamic-component :component="'lucide-'.$icon" class="h-8 w-8"/>
                    </span>
                @endisset
                <div>
                    <h1 class="text-3xl sm:text-5xl"><span class="mark">{{ $heading }}</span></h1>
                    @isset($intro)
                        <p class="mt-5 max-w-3xl text-lg text-slate-600">{{ $intro }}</p>
                    @endisset
                    @isset($actions)
                        <div class="mt-6 flex flex-wrap gap-3">{!! $actions !!}</div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
