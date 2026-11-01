@php
    $current = app()->getLocale();
    $currentPath = '/'.ltrim(request()->path(), '/');
    $restPath = preg_replace('#^/[a-z]{2}(?=/|$)#', '', $currentPath);
@endphp
<div class="flex h-10 items-center gap-1 rounded-xl border border-slate-200 p-1" aria-label="{{ __('site.lang') }}">
    @foreach (config('site.locales') as $code => $meta)
        <a href="{{ url('/'.$code.$restPath) }}" hreflang="{{ $code }}"
           class="flex h-full items-center rounded-lg px-3 text-sm font-bold {{ $code === $current ? 'bg-brand-500 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            {{ $meta['name'] }}
        </a>
    @endforeach
</div>
