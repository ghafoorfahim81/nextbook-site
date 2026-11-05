{{-- A feature note: the title, the summary and the first three capabilities, each on its own ruled line. --}}
@php($highlights = array_slice((array) $feature->highlights, 0, 3))
<a href="{{ route('features.show', $feature) }}" class="note-card group h-full">
    @include('partials.note-mark', ['icon' => $feature->icon])

    <h3 class="text-lg">
        {{ $feature->title }}
        @if ($feature->status === 'soon')
            <span class="ms-1 rounded-full bg-amber-100 px-2.5 py-0.5 align-middle text-xs font-bold text-amber-800">{{ __('site.features.soon') }}</span>
        @endif
    </h3>

    <p class="text-sm text-slate-600">{{ $feature->summary }}</p>

    @if ($highlights)
        <ul class="text-sm text-slate-700">
            @foreach ($highlights as $highlight)
                <li class="flex items-start gap-2"><x-lucide-check class="mt-2 h-4 w-4 shrink-0 text-brand-500"/>{{ $highlight['title'] }}</li>
            @endforeach
        </ul>
    @endif

    <span class="row mt-8 flex items-center gap-1 text-sm font-bold text-brand-600">
        {{ __('site.read_more') }}<x-lucide-arrow-right class="h-4 w-4 transition group-hover:-translate-x-1 ltr:rotate-180 ltr:group-hover:translate-x-1"/>
    </span>
</a>
