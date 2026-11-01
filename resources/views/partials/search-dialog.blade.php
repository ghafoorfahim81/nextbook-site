{{-- The search box that opens over the page (Ctrl/Cmd+K, or "/"). Results arrive as the visitor types. --}}
<dialog id="search-dialog" aria-label="{{ __('site.search.title') }}"
        data-endpoint="{{ route('search') }}"
        data-none="{{ __('site.search.none', ['q' => ':q']) }}"
        data-error="{{ __('site.search.error') }}"
        data-all="{{ __('site.search.all') }}"
        class="m-0 mx-auto mt-[10vh] w-[min(42rem,calc(100%-2rem))] rounded-2xl border border-slate-200 bg-surface p-0 text-slate-700 shadow-2xl backdrop:bg-black/50 backdrop:backdrop-blur-sm">
    <form method="GET" action="{{ route('search') }}" role="search" class="flex items-center gap-3 border-b border-slate-200 px-4">
        <x-lucide-search class="h-5 w-5 shrink-0 text-slate-400"/>
        <input id="search-input" name="q" type="search" autocomplete="off" autocapitalize="none" spellcheck="false"
               placeholder="{{ __('site.search.placeholder') }}" aria-label="{{ __('site.search.title') }}"
               role="combobox" aria-expanded="false" aria-controls="search-results" aria-autocomplete="list"
               class="h-14 min-w-0 flex-1 bg-transparent text-base text-slate-900 outline-none placeholder:text-slate-400">
        <button type="button" data-search-close class="rounded-md border border-slate-200 px-2 py-1 text-xs text-slate-500 hover:bg-slate-100" aria-label="{{ __('site.search.close') }}">Esc</button>
    </form>

    <div id="search-results" role="listbox" class="max-h-[60vh] overflow-y-auto p-2">
        {{-- Shown while the box is empty. --}}
        <div id="search-quick">
            <p class="px-3 pb-1 pt-2 text-xs font-bold uppercase tracking-wide text-slate-400">{{ __('site.search.quick') }}</p>
            @foreach ([
                ['features.index', 'features', 'sparkles'], ['industries.index', 'industries', 'store'], ['pricing', 'pricing', 'badge-dollar-sign'],
                ['help.index', 'help', 'book-open'], ['blog.index', 'blog', 'newspaper'], ['contact', 'contact', 'mail'],
            ] as [$routeName, $key, $icon])
                <a href="{{ route($routeName) }}" role="option" data-result class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-700 hover:bg-brand-50">
                    <x-dynamic-component :component="'lucide-'.$icon" class="h-4 w-4 text-brand-500"/>{{ __('site.nav.'.$key) }}
                </a>
            @endforeach
        </div>
        {{-- Filled by JavaScript. --}}
        <div id="search-live" hidden></div>
    </div>

    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 border-t border-slate-200 px-4 py-2 text-xs text-slate-400">
        <span>↑ ↓ {{ __('site.search.keys_move') }}</span>
        <span>Enter {{ __('site.search.keys_open') }}</span>
        <span>Esc {{ __('site.search.keys_close') }}</span>
    </div>
</dialog>
