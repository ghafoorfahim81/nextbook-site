@php
    use App\Support\Digits;
    use App\Support\PostDate;
@endphp
<a href="{{ route('blog.show', $post) }}" class="card card-hover group flex h-full flex-col">
    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-md shadow-brand-500/25">
        <x-dynamic-component :component="'lucide-'.$post->icon" class="h-6 w-6"/>
    </span>
    <h3 class="mt-5 text-lg">{{ $post->title }}</h3>
    <p class="mt-2 text-sm text-slate-600">{{ $post->excerpt }}</p>
    <p class="mt-auto flex flex-wrap items-center gap-x-3 pt-5 text-xs text-slate-500">
        <span>{{ PostDate::format($post->published_at) }}</span>
        <span>{{ __('site.blog.minutes', ['n' => Digits::localize($post->readingMinutes())]) }}</span>
    </p>
</a>
