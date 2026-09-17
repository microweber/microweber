<?php

/*

type: layout

name: Features 5 - Cards with CTA

position: 5

categories: Features

*/

?>

@php
    $items = [
        ['icon' => 'mw-micon-Certified-Badge',    'title' => 'Trusted & certified', 'text' => 'Backed by a 99.9% uptime SLA and independent security audits you can rely on.'],
        ['icon' => 'mw-micon-CreditCard-Payment', 'title' => 'Simple, fair pricing',  'text' => 'Transparent plans with no hidden fees. The price you see is the price you pay.'],
        ['icon' => 'mw-micon-Speed-Fast',          'title' => 'Fast by default',      'text' => 'NVMe storage and HTTP/3 deliver up to 20x faster load times out of the box.'],
    ];
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section features-skin-5"
    field-name="layout-features-skin-5"
>
    <x-row class="text-center safe-mode mb-4">
        <x-col size="12" size-lg="8" class="mx-auto">
            <x-section-heading tag="h2" subtitle="Explore what makes our platform the smart choice for modern teams." class="display-5 fw-bold">Features worth exploring</x-section-heading>
        </x-col>
    </x-row>

    <x-row class="row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-3">
        @foreach($items as $item)
            <div class="col cloneable element safe-mode">
                <div class="card h-100 border-0 shadow-sm background-color-element">
                    <div class="card-body d-flex flex-column p-4">
                        <i class="{{ $item['icon'] }} fs-1 text-primary mb-3 safe-element no-typing"></i>
                        <h5 class="fw-semibold" data-mwplaceholder="Enter title here">{{ $item['title'] }}</h5>
                        <p class="text-muted small regular-mode" data-mwplaceholder="Enter text here">{{ $item['text'] }}</p>
                        <a href="#" class="fw-semibold text-primary text-decoration-none mt-auto">Learn more &rarr;</a>
                    </div>
                </div>
            </div>
        @endforeach
    </x-row>
</x-layout-section>
