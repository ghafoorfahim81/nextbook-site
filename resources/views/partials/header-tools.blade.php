{{-- Search and the light/dark switch, as icons: the header has no room for more in a longer language.
     Without JavaScript the search link goes to the results page. --}}
<a href="{{ route('search') }}" data-search-open aria-label="{{ __('site.search.open') }}" title="{{ __('site.search.open') }} (Ctrl K)"
   class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-slate-600 hover:bg-slate-100">
    <x-lucide-search class="h-[18px] w-[18px]"/>
</a>

<button type="button" data-theme-toggle aria-pressed="false" aria-label="{{ __('site.theme.toggle') }}" title="{{ __('site.theme.toggle') }}"
        class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-slate-600 hover:bg-slate-100">
    <x-lucide-moon class="h-[18px] w-[18px] dark:hidden"/>
    <x-lucide-sun class="hidden h-[18px] w-[18px] dark:block"/>
</button>
