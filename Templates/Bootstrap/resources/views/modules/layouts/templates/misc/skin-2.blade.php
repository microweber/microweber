<?php
/*
type: layout
name: Misc 2 - Client Logos
position: 2
categories: Misc
*/
?>
@php
    $logoItems = ['Northwind', 'Acme Corp', 'Globex', 'Initech', 'Umbrella', 'Soylent', 'Hooli', 'Stark Ind.'];
@endphp
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section misc-skin-2 py-6"
    field-name="layout-misc-skin-2"
>
    <x-row class="text-center mb-4">
        <x-col size="12" size-lg="8" class="mx-auto">
            <x-section-heading tag="h2" subtitle="Leading companies around the world rely on us to power their growth." class="h3 fw-bold">Trusted by</x-section-heading>
        </x-col>
    </x-row>

    <div class="row row-cols-2 row-cols-md-4 g-4 align-items-center">
        @foreach($logoItems as $logo)
            <div class="col">
                <div class="ratio ratio-21x9 bg-light rounded d-flex align-items-center justify-content-center">
                    <span class="position-absolute top-50 start-50 translate-middle text-muted fw-semibold text-uppercase small" data-mwplaceholder="Enter name here">{{ $logo }}</span>
                </div>
            </div>
        @endforeach
    </div>
</x-layout-section>
