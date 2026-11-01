{{-- Search and the light/dark switch. Without JavaScript the search link goes to the results page. --}}
<a href="{{ route('search') }}" data-search-open aria-label="{{ __('site.search.open') }}" title="{{ __('site.search.open') }}"
   class="inline-flex h-9 items-center gap-2 rounded-lg border border-transparent px-2.5 text-[13px] text-slate-600 hover:bg-slate-100 2xl:border-slate-200 2xl:bg-surface 2xl:text-slate-500 2xl:hover:border-brand-300">
    <x-lucide-search class="h-[18px] w-[18px]"/>
    <span class="hidden 2xl:inline">{{ __('site.search.open') }}</span>
    <kbd dir="ltr" class="hidden rounded border border-slate-200 px-1.5 py-0.5 text-[11px] font-medium text-slate-400 2xl:inline">Ctrl K</kbd>
</a>

<button type="button" data-theme-toggle aria-pressed="false" aria-label="{{ __('site.theme.toggle') }}" title="{{ __('site.theme.toggle') }}"
        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-600 hover:bg-slate-100">
    <x-lucide-moon class="h-[18px] w-[18px] dark:hidden"/>
    <x-lucide-sun class="hidden h-[18px] w-[18px] dark:block"/>
</button>
