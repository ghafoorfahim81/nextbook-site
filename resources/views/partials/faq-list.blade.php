<div class="divide-y divide-slate-200 rounded-2xl border border-slate-200 bg-white">
    @foreach ($faqs as $faq)
        <details class="group p-5" @if ($loop->first && ($open ?? false)) open @endif>
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-bold text-slate-900">
                {{ $faq->question }}
                <x-lucide-chevron-down class="h-5 w-5 shrink-0 text-brand-500 transition group-open:rotate-180"/>
            </summary>
            <p class="mt-3 text-slate-600">{{ $faq->answer }}</p>
        </details>
    @endforeach
</div>
