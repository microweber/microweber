<?php
/*
type: layout
name: Titles 3 - Left eyebrow lead
position: 3
categories: Titles
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section titles-skin-3"
    field-name="layout-titles-skin-3"
>
    <x-row>
        <x-col size-lg="7">
            <div class="text-primary text-uppercase small fw-bold mb-2" data-mwplaceholder="Enter text here">Our approach</div>
            <h2 class="fw-bold mb-3" data-mwplaceholder="Enter title here">Built around your goals</h2>
            <p class="lead text-muted mb-0" data-mwplaceholder="Enter text here">We keep things focused and practical, pairing clear strategy with careful execution so every detail moves you closer to the result you want.</p>
        </x-col>
    </x-row>
</x-layout-section>
