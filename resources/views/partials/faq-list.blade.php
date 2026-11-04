<div class="space-y-3">
    @foreach ($faqs as $faq)
        <details id="faq-{{ $faq->id }}" class="group scroll-mt-24 rounded-2xl border border-slate-200 bg-surface p-5 transition open:border-brand-300 open:bg-brand-50/60 open:shadow-sm" @if ($loop->first && ($open ?? false)) open @endif>
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-bold text-slate-900">
                {{ $faq->question }}
                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-100 text-brand-600 transition group-open:rotate-180 group-open:bg-brand-500 group-open:text-white">
                    <x-lucide-chevron-down class="h-4 w-4"/>
                </span>
            </summary>
            <p class="mt-3 text-slate-600">{{ $faq->answer }}</p>
        </details>
    @endforeach
</div>
