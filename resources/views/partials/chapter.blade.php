{{-- A chapter heading: a big outlined numeral, the eyebrow, the title and a line of text. --}}
<div class="reveal mb-12 flex max-w-3xl items-start gap-5">
    @isset($no)
        <span aria-hidden="true" class="chapter-no hidden text-7xl sm:block">{{ \App\Support\Digits::localize($no) }}</span>
    @endisset
    <div>
        <p class="eyebrow">{{ $eyebrow }}</p>
        <h2 class="text-3xl sm:text-4xl">{{ $title }}</h2>
        @isset($text)<p class="mt-4 text-lg text-slate-600">{{ $text }}</p>@endisset
    </div>
</div>
