<?php
/*
type: layout
name: Header 4 - Hero with Stats Row
position: 4
categories: Header
*/
?>

@php
    $headerStats = [
        ['icon' => 'mw-micon-Speed-Fast', 'value' => '99.9%', 'label' => 'Uptime guaranteed'],
        ['icon' => 'mw-micon-Shield-Protected', 'value' => '24/7', 'label' => 'Expert support'],
        ['icon' => 'mw-micon-Globe-Earth', 'value' => '120+', 'label' => 'Countries served'],
        ['icon' => 'mw-micon-Star', 'value' => '4.9/5', 'label' => 'Customer rating'],
    ];
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section header-skin-4-stats text-center"
    field-name="layout-header-skin-4"
    default-padding-top="pt-6"
    default-padding-bottom="pb-6"
>
    <x-row class="justify-content-center">
        <x-col size="12" size-lg="9" class="mx-auto allow-select regular-mode">
            <h1 data-mwplaceholder="Enter title here" class="display-3 fw-bold mb-4">Everything you need, all in one platform</h1>
            <p data-mwplaceholder="Enter text here" class="lead text-muted mb-5 mx-auto" style="max-width: 640px;">Powerful, reliable and loved by teams around the world. Join thousands of businesses already growing with us.</p>
            <module type="btn" button_style="btn-primary" button_size="btn-lg px-5" text="Start your free trial"/>
        </x-col>
    </x-row>

    <x-row class="row-cols-2 row-cols-lg-4 g-4 mt-4 text-center">
        @foreach($headerStats as $stat)
            <div class="col cloneable element safe-mode">
                <div class="p-3">
                    <i class="mb-2 safe-element no-typing {{ $stat['icon'] }} d-block fs-1 text-primary"></i>
                    <div class="display-6 fw-bold" data-mwplaceholder="Enter title here">{{ $stat['value'] }}</div>
                    <p class="text-muted small mb-0 regular-mode" data-mwplaceholder="Enter text here">{{ $stat['label'] }}</p>
                </div>
            </div>
        @endforeach
    </x-row>
</x-layout-section>
