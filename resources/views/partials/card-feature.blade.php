{{-- A feature card: icon, title, summary, the first three capabilities, and a way in. --}}
@php($highlights = array_slice((array) $feature->highlights, 0, 3))
<a href="{{ route('features.show', $feature) }}" class="card card-hover group flex h-full flex-col">
    <div class="flex items-center gap-4">
        <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-md shadow-brand-500/25">
            <x-dynamic-component :component="'lucide-'.$feature->icon" class="h-6 w-6"/>
        </span>
        <h3 class="text-lg">{{ $feature->title }}</h3>
        @if ($feature->status === 'soon')
            <span class="ms-auto shrink-0 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">{{ __('site.features.soon') }}</span>
        @endif
    </div>

    <p class="mt-4 text-sm text-slate-600">{{ $feature->summary }}</p>

    @if ($highlights)
        <ul class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-sm text-slate-700">
            @foreach ($highlights as $highlight)
                <li class="flex items-start gap-2"><x-lucide-check class="mt-0.5 h-4 w-4 shrink-0 text-brand-500"/>{{ $highlight['title'] }}</li>
            @endforeach
        </ul>
    @endif

    <span class="mt-auto inline-flex items-center gap-1 pt-5 text-sm font-bold text-brand-600">
        {{ __('site.read_more') }}<x-lucide-arrow-right class="h-4 w-4 transition group-hover:-translate-x-1 ltr:rotate-180 ltr:group-hover:translate-x-1"/>
    </span>
</a>
