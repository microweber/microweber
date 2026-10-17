@php

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
    {{-- audit-test 2026-05-07 post-merge follow-up #1 (TICKET-AT, SECURITY HIGH):
         JSON_UNESCAPED_SLASHES disabled the default `\/` escape, so an
         admin-supplied question/answer containing `</script>` literally
         terminated the LD+JSON block at render time and the surrounding
         HTML was injected into the page. Switched to JSON_HEX_TAG which
         escapes `<` and `>` as `<` / `>` — eliminates ALL
         tag-breakout shapes (not just `</script>`), no functional change
         for legitimate inputs (Google's Schema.org validator accepts
         hex-escaped tag chars), same character count as the prior flag
         set. Cycle-28 finding #5 was incorrectly marked VERIFIED CORRECT;
         this closes that retraction. --}}
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqJsonLdEntities,
        ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
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

@once
    <link rel="stylesheet" href="{{ asset('modules/faq/css/faq-skin.css') }}">
@endonce
