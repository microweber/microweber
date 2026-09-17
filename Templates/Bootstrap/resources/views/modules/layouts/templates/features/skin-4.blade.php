<?php

/*

type: layout

name: Features 4 - Alternating Rows

position: 4

categories: Features

*/

?>

@php
    $items = [
        ['icon' => 'mw-micon-Globe-Earth',     'title' => 'Reach a global audience', 'text' => 'Serve your content from 200+ edge locations so visitors everywhere get the closest, fastest copy of your site.', 'reverse' => false],
        ['icon' => 'mw-micon-ArrowUp-Growth',  'title' => 'Scale without limits',    'text' => 'Handle traffic spikes with one-click upgrades. No migrations, no downtime — just room to grow whenever you need it.', 'reverse' => true],
    ];
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section features-skin-4"
    field-name="layout-features-skin-4"
>
    <x-row class="text-center safe-mode mb-4">
        <x-col size="12" size-lg="8" class="mx-auto">
            <x-section-heading tag="h2" subtitle="Two big reasons teams choose us to power their most important work." class="display-5 fw-bold">Designed to grow with you</x-section-heading>
        </x-col>
    </x-row>

    @foreach($items as $item)
        <div class="row align-items-center g-5 py-4 {{ $item['reverse'] ? 'flex-lg-row-reverse' : '' }} cloneable element safe-mode background-color-element">
            <div class="col-12 col-lg-6">
                <div class="ratio ratio-4x3 bg-light rounded"></div>
            </div>
            <div class="col-12 col-lg-6">
                <i class="{{ $item['icon'] }} fs-1 text-primary mb-3 d-block safe-element no-typing"></i>
                <h3 class="fw-bold" data-mwplaceholder="Enter title here">{{ $item['title'] }}</h3>
                <p class="text-muted regular-mode" data-mwplaceholder="Enter text here">{{ $item['text'] }}</p>
                <div class="mt-3">
                    <module type="btn" id="{{ $params['id'] }}-feat{{ $loop->index }}" button_style="btn-dark" button_size="btn-md" button_text="Learn More"/>
                </div>
            </div>
        </div>
    @endforeach
</x-layout-section>
