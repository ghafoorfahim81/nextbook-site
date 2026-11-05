<form method="GET" action="{{ route('help.search') }}" class="{{ $class ?? 'mx-auto mt-6 max-w-xl' }} flex gap-2" role="search">
    <label for="help-q" class="sr-only">{{ __('site.help.search') }}</label>
    <input id="help-q" name="q" value="{{ $q ?? '' }}" class="input flex-1 py-3" placeholder="{{ __('site.help.search_placeholder') }}" minlength="2" required>
    <button class="btn btn-primary px-5"><x-lucide-search class="h-4 w-4"/>{{ __('site.help.search_btn') }}</button>
</form>
