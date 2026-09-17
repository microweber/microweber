<?php
/*
type: layout
name: Grids 3 - Compact Stats Grid
position: 3
categories: Grids
*/
?>
@php
    $statItems = [
        ['icon' => 'mw-micon-ArrowUp-Growth',    'number' => '250%', 'label' => 'Average growth'],
        ['icon' => 'mw-micon-Globe-Earth',        'number' => '190+', 'label' => 'Countries served'],
        ['icon' => 'mw-micon-Star',               'number' => '4.9',  'label' => 'Customer rating'],
        ['icon' => 'mw-micon-Speed-Fast',         'number' => '0.4s', 'label' => 'Avg load time'],
    ];
@endphp
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section grids-skin-3"
    field-name="layout-grids-skin-3"
>
    <x-row class="text-center safe-mode">
        <x-col size="12" size-lg="8" size-xl="8" size-xxl="8" class="mx-auto">
            <div class="regular-mode">
                <x-section-heading tag="h2" subtitle="Numbers that speak for themselves." class="fw-bold">Trusted at scale</x-section-heading>
            </div>
        </x-col>
    </x-row>

    <div class="row row-cols-2 row-cols-lg-4 g-4 mt-3">
        @foreach($statItems as $item)
            <div class="col cloneable element safe-mode">
                <div class="h-100 p-4 bg-light rounded text-center">
                    <i class="safe-element no-typing {{ $item['icon'] }} d-block fs-2 text-primary mb-2"></i>
                    <div class="display-6 fw-bold" data-mwplaceholder="Enter title here">{{ $item['number'] }}</div>
                    <div class="text-muted small regular-mode" data-mwplaceholder="Enter text here">{{ $item['label'] }}</div>
                </div>
            </div>
        @endforeach
    </div>
</x-layout-section>
