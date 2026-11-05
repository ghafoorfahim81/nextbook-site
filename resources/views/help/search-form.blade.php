{{-- One long search field with the button inside it, like a real search bar. --}}
<form method="GET" action="{{ route('help.search') }}" class="{{ $class ?? 'mx-auto mt-6 w-full max-w-4xl' }}" role="search">
    <label for="help-q" class="sr-only">{{ __('site.help.search') }}</label>
    <div class="flex items-center gap-2 rounded-full border border-brand-200 bg-surface p-1.5 ps-5 shadow-sm transition focus-within:border-brand-500 focus-within:ring-2 focus-within:ring-brand-200">
        <x-lucide-search class="h-5 w-5 shrink-0 text-slate-400"/>
        <input id="help-q" name="q" value="{{ $q ?? '' }}" minlength="2" required
               class="min-w-0 flex-1 border-0 bg-transparent py-2 text-base text-slate-800 outline-none placeholder:text-slate-400"
               placeholder="{{ __('site.help.search_placeholder') }}">
        <button class="btn btn-primary shrink-0 px-7 py-2.5 text-base">{{ __('site.help.search_btn') }}</button>
    </div>
</form>
