<div class="border-b border-slate-200 bg-gradient-to-b from-brand-50 to-white">
    <div class="container-page py-12 sm:py-16">
        @isset($crumb)
            <a href="{{ $crumb['url'] }}" class="mb-3 inline-flex items-center gap-1 text-sm font-medium text-brand-600 hover:underline">
                <x-lucide-arrow-right class="h-4 w-4 rtl:rotate-0 ltr:rotate-180"/>{{ $crumb['label'] }}
            </a>
        @endisset
        <h1 class="text-3xl sm:text-4xl">{{ $heading }}</h1>
        @isset($intro)
            <p class="mt-3 max-w-3xl text-lg text-slate-600">{{ $intro }}</p>
        @endisset
    </div>
</div>
