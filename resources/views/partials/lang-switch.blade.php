@php
    $current = app()->getLocale();
    $currentPath = '/'.ltrim(request()->path(), '/');
    $restPath = preg_replace('#^/[a-z]{2}(?=/|$)#', '', $currentPath);
@endphp
<div class="flex items-center gap-1 rounded-xl border border-slate-200 p-0.5" aria-label="{{ __('site.lang') }}">
    @foreach (config('site.locales') as $code => $meta)
        <a href="{{ url('/'.$code.$restPath) }}" hreflang="{{ $code }}"
           class="rounded-lg px-2.5 py-1 text-xs font-bold {{ $code === $current ? 'bg-brand-500 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            {{ $meta['name'] }}
        </a>
    @endforeach
</div>
