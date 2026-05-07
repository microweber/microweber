@php
    /*
     * task-2026-05-05-3bd724 — drunk-designer audit (faq.md): emit
     * FAQPage JSON-LD so search engines render the Q&A as a rich
     * snippet on results pages. Skips entries with no question or
     * no answer — those would confuse crawlers.
     */
    $faqJsonLdEntities = [];
    if (isset($faqs) && !empty($faqs) && is_array($faqs)) {
        foreach ($faqs as $faqJsonLdItem) {
            if (empty($faqJsonLdItem['question']) || empty($faqJsonLdItem['answer'])) {
                continue;
            }
            $faqJsonLdEntities[] = [
                '@type' => 'Question',
                'name' => $faqJsonLdItem['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faqJsonLdItem['answer'],
                ],
            ];
        }
    }
@endphp

@if(!empty($faqJsonLdEntities))
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqJsonLdEntities,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endif

{{-- audit-test 2026-05-07 FAQ audit findings #1 + #2 + #4:
     #1 (UX HIGH): module was labelled FAQ but rendered every Q+A as
     always-visible <h4>+<p> pairs — defeats the FAQ-skim-then-expand
     UX. Switched to native <details>/<summary>: zero-JS, screen-
     reader-friendly, keyboard-accessible, perfect WAI-ARIA disclosure
     semantics for free. Google FAQPage rich-snippet rules accept
     collapsed-but-in-DOM answers, so JSON-LD continues to qualify.
     #2 (A11Y): <h4> wasn't the right semantic for FAQ questions —
     `<summary>` is the disclosure trigger, no heading level needed.
     #4 (i18n): wrapped fallback strings in __() for translation.
     #3 (CSS extraction to stylesheet) deferred to TICKET-AI. --}}
<div class="faq-holder">
    <div class="faq-list">
        @if(isset($faqs) && !empty($faqs))
            @foreach($faqs as $faq)
                <details class="faq-item">
                    <summary>{{ !empty($faq['question']) ? $faq['question'] : __('No question provided') }}</summary>
                    <p>{{ !empty($faq['answer']) ? $faq['answer'] : __('No answer provided') }}</p>
                </details>
            @endforeach
        @else
            <p>{{ __('No FAQs added to the module. Please add your FAQ to see the content...') }}</p>
        @endif
    </div>
</div>

<style>
    .faq-holder {
        margin: 20px;
    }

    .faq-item {
        border: 1px solid #efefef;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
    }

    .faq-item > summary {
        margin: 0 0 10px;
        font-size: 18px;
        color: #1157c1;
        cursor: pointer;
        list-style: revert;
    }

    .faq-item[open] > summary {
        margin-bottom: 10px;
    }

    .faq-item p {
        margin: 0;
        font-size: 14px;
        color: #6f6f6f;
    }
</style>
