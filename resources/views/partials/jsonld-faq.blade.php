{{-- FAQ rich results: the same questions and answers that are on the page. --}}
@if ($faqs->isNotEmpty())
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => $faqs->map(fn ($faq) => [
        '@type' => 'Question',
        'name' => $faq->question,
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->answer],
    ])->values()->all(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endif
