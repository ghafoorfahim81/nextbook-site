{{-- What customers say. Shown only when config('site.show_testimonials') is on. --}}
@if ($testimonials->isNotEmpty())
<section class="section">
    <div class="container-page">
        @include('partials.chapter', ['eyebrow' => __('site.testimonials.eyebrow'), 'title' => __('site.testimonials.title')])
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($testimonials as $t)
                <figure class="card reveal flex min-w-0 flex-col" style="--d: {{ $loop->index * 0.08 }}s">
                    <x-lucide-quote class="h-8 w-8 text-accent-500"/>
                    <blockquote class="mt-4 text-slate-700">{{ $t->text('quote') }}</blockquote>

                    @if ($media = $t->media())
                        <div class="mt-4 space-y-3">
                            @foreach ($media as $m)
                                @if ($m['type'] === 'image')
                                    <button type="button" data-zoom data-close="{{ __('site.story.shots.close') }}" class="block w-full cursor-zoom-in overflow-hidden rounded-xl border border-slate-200">
                                        <img src="{{ $m['url'] }}" alt="" loading="lazy" class="block max-h-64 w-full object-cover">
                                    </button>
                                @elseif ($m['type'] === 'video')
                                    <video src="{{ $m['url'] }}" controls preload="metadata" playsinline class="w-full rounded-xl bg-black"></video>
                                @else
                                    <audio src="{{ $m['url'] }}" controls preload="none" class="w-full"></audio>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <figcaption class="mt-auto flex items-center gap-3 pt-6">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-brand-50 font-bold text-brand-700">{{ mb_substr((string) $t->text('name'), 0, 1) }}</span>
                        <span>
                            <span class="block font-bold text-slate-900">{{ $t->text('name') }}</span>
                            @if ($t->text('role'))<span class="block text-sm text-slate-500">{{ $t->text('role') }}</span>@endif
                        </span>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif
