<form method="GET" action="{{ route('help.search') }}" class="{{ $class ?? 'mx-auto mt-6 max-w-2xl' }} flex gap-2" role="search">
    <label for="help-q" class="sr-only">{{ __('site.help.search') }}</label>
    <input id="help-q" name="q" value="{{ $q ?? '' }}" class="input flex-1 !rounded-full !py-4 !ps-6 text-base shadow-sm" placeholder="{{ __('site.help.search_placeholder') }}" minlength="2" required>
    <button class="btn btn-primary px-8 py-4 text-base"><x-lucide-search class="h-4 w-4"/>{{ __('site.help.search_btn') }}</button>
</form>
