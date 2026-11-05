@php
    use App\Support\Digits;
    use App\Support\PostDate;
@endphp
<a href="{{ route('blog.show', $post) }}" class="note-card group h-full">
    @include('partials.note-mark', ['icon' => $post->icon])
    <h3 class="text-lg">{{ $post->title }}</h3>
    <p class="text-sm text-slate-600">{{ $post->excerpt }}</p>
    <p class="row mt-8 flex flex-wrap items-center gap-x-3 text-xs text-slate-500">
        <span class="inline-flex items-center gap-1"><x-lucide-calendar-clock class="h-3.5 w-3.5"/>{{ PostDate::format($post->published_at) }}</span>
        <span class="inline-flex items-center gap-1"><x-lucide-clock class="h-3.5 w-3.5"/>{{ __('site.blog.minutes', ['n' => Digits::localize($post->readingMinutes())]) }}</span>
    </p>
</a>
