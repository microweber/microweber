<?php
/*
type: layout
name: Testimonials 3 - Single spotlight
position: 3
categories: Testimonials
*/
?>
@php
    $quotes = [
        ['text' => 'Switching to this platform was the best decision we made all year. Our whole team is faster, happier, and more aligned than ever before.', 'name' => 'Amara Okafor', 'role' => 'CEO, Lumen Studio'],
    ];
    $quote = $quotes[0];
@endphp
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section testimonials-skin-3"
    field-name="layout-testimonials-skin-3"
>
    <x-row class="text-center">
        <x-col size="12" size-lg="9" size-xl="8" class="mx-auto">
            <div class="mb-3">
                <i class="mw-micon-Star text-warning"></i>
                <i class="mw-micon-Star text-warning"></i>
                <i class="mw-micon-Star text-warning"></i>
                <i class="mw-micon-Star text-warning"></i>
                <i class="mw-micon-Star text-warning"></i>
            </div>
            <p class="lead fs-3 fw-normal" data-mwplaceholder="Quote text">&ldquo;{{ $quote['text'] }}&rdquo;</p>
            <div class="d-flex flex-column align-items-center mt-4">
                <div class="ratio ratio-1x1 rounded-circle bg-light mb-3" style="max-width:48px;"></div>
                <p class="fw-semibold mb-0" data-mwplaceholder="Name">{{ $quote['name'] }}</p>
                <p class="text-muted mb-0" data-mwplaceholder="Role">{{ $quote['role'] }}</p>
            </div>
        </x-col>
    </x-row>
</x-layout-section>
