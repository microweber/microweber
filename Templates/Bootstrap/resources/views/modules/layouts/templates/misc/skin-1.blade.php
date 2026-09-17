<?php
/*
type: layout
name: Misc 1 - Stats Counters
position: 1
categories: Misc
*/
?>
@php
    $statItems = [
        ['icon' => 'mw-micon-Users-Group',       'number' => '12,000+', 'label' => 'Happy customers'],
        ['icon' => 'mw-micon-Globe-Earth',        'number' => '48',      'label' => 'Countries served'],
        ['icon' => 'mw-micon-Certified-Badge',    'number' => '99.9%',   'label' => 'Uptime guarantee'],
        ['icon' => 'mw-micon-ArrowUp-Growth',     'number' => '3.5x',    'label' => 'Average growth'],
    ];
@endphp
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section misc-skin-1 py-6"
    field-name="layout-misc-skin-1"
>
    <x-row class="text-center mb-4">
        <x-col size="12" size-lg="8" class="mx-auto">
            <x-section-heading tag="h2" subtitle="Numbers that speak for themselves — here is the impact we deliver every day." class="display-5 fw-bold">Trusted results at scale</x-section-heading>
        </x-col>
    </x-row>

    <div class="row row-cols-2 row-cols-lg-4 text-center g-4">
        @foreach($statItems as $stat)
            <div class="col">
                <i class="{{ $stat['icon'] }} d-block fs-1 text-primary mb-2"></i>
                <div class="display-4 fw-bold text-primary" data-mwplaceholder="Enter number here">{{ $stat['number'] }}</div>
                <p class="text-muted mb-0" data-mwplaceholder="Enter label here">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>
</x-layout-section>
