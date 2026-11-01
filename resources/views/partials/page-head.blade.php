<div class="relative isolate border-b border-slate-200 bg-gradient-to-b from-brand-50/70 to-surface">
    @include('partials.backdrop')
    <div class="container-page py-12 sm:py-16">
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
                <h1 class="text-3xl sm:text-4xl">{{ $heading }}</h1>
                @isset($intro)
                    <p class="mt-3 max-w-3xl text-lg text-slate-600">{{ $intro }}</p>
                @endisset
                @isset($actions)
                    <div class="mt-6 flex flex-wrap gap-3">{!! $actions !!}</div>
                @endisset
            </div>
        </div>
    </div>
</div>
