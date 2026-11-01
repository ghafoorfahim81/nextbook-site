@php
    $current = app()->getLocale();
    $currentPath = '/'.ltrim(request()->path(), '/');
    $restPath = preg_replace('#^/[a-z]{2}(?=/|$)#', '', $currentPath);
@endphp
<div class="flex h-9 items-center gap-0.5 rounded-lg border border-slate-200 p-0.5" aria-label="{{ __('site.lang') }}">
    @foreach (config('site.locales') as $code => $meta)
        <a href="{{ url('/'.$code.$restPath) }}" hreflang="{{ $code }}"
           class="flex h-full items-center rounded-md px-2.5 text-[13px] font-bold {{ $code === $current ? 'bg-brand-500 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            {{ $meta['name'] }}
        </a>
    @endforeach
</div>
