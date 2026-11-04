@php
    use App\Support\Digits;
    use App\Support\PostDate;
@endphp
<a href="{{ route('blog.show', $post) }}" class="note-card group flex h-full flex-col">
    <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-md shadow-brand-500/25">
        <x-dynamic-component :component="'lucide-'.$post->icon" class="h-6 w-6"/>
    </span>
    <h3 class="mt-5 text-lg">{{ $post->title }}</h3>
    <p class="mt-2 text-sm text-slate-600">{{ $post->excerpt }}</p>
    <p class="mt-auto flex flex-wrap items-center gap-x-3 gap-y-1 border-t border-dashed border-slate-300 pt-4 text-xs text-slate-500">
        <span class="inline-flex items-center gap-1"><x-lucide-calendar-clock class="h-3.5 w-3.5"/>{{ PostDate::format($post->published_at) }}</span>
        <span class="inline-flex items-center gap-1"><x-lucide-clock class="h-3.5 w-3.5"/>{{ __('site.blog.minutes', ['n' => Digits::localize($post->readingMinutes())]) }}</span>
    </p>
</a>
