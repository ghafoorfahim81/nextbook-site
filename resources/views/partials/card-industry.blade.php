{{-- An industry card: icon, title, summary, what is different for this trade, and a way in. --}}
@php($highlights = array_slice((array) $industry->highlights, 0, 3))
<a href="{{ route('industries.show', $industry) }}" class="note-card group flex h-full flex-col">
    <div class="flex items-center gap-4">
        <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-md shadow-brand-500/25">
            <x-dynamic-component :component="'lucide-'.$industry->icon" class="h-6 w-6"/>
        </span>
        <h3 class="text-lg">{{ $industry->title }}</h3>
    </div>

    <p class="mt-4 text-sm text-slate-600">{{ $industry->summary }}</p>

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
