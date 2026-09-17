<?php
/*
type: layout
name: Misc 3 - FAQ Accordion
position: 3
categories: Misc
*/
?>
@php
    $faqItems = [
        ['q' => 'How do I get started?',              'a' => 'Sign up for a free account, pick a template, and your site is live in minutes — no credit card required.'],
        ['q' => 'Can I use my own domain?',           'a' => 'Yes. Connect an existing domain or register a new one directly from your dashboard in just a few clicks.'],
        ['q' => 'Is there a money-back guarantee?',    'a' => 'Absolutely. Every paid plan comes with a 30-day money-back guarantee, no questions asked.'],
        ['q' => 'Do you offer customer support?',     'a' => 'Our team is available 24/7 by chat and email, with an average first response time under three minutes.'],
    ];
    $accId = $params['id'] . '-misc-faq';
@endphp
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section misc-skin-3 py-6"
    field-name="layout-misc-skin-3"
>
    <x-row class="text-center mb-4">
        <x-col size="12" size-lg="8" class="mx-auto">
            <x-section-heading tag="h2" subtitle="Everything you need to know before getting started — answered." class="display-5 fw-bold">Frequently asked questions</x-section-heading>
        </x-col>
    </x-row>

    <x-row class="justify-content-center">
        <x-col size="12" size-lg="8" class="mx-auto">
            <div class="accordion" id="{{ $accId }}">
                @foreach($faqItems as $i => $faq)
                    @php
                        $itemId = $accId . '-item-' . $i;
                        $isFirst = $loop->first;
                    @endphp
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="{{ $itemId }}-heading">
                            <button class="accordion-button {{ $isFirst ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $itemId }}-collapse" aria-expanded="{{ $isFirst ? 'true' : 'false' }}" aria-controls="{{ $itemId }}-collapse" data-mwplaceholder="Enter question here">{{ $faq['q'] }}</button>
                        </h3>
                        <div id="{{ $itemId }}-collapse" class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}" aria-labelledby="{{ $itemId }}-heading" data-bs-parent="#{{ $accId }}">
                            <div class="accordion-body text-muted" data-mwplaceholder="Enter answer here">{{ $faq['a'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-col>
    </x-row>
</x-layout-section>
