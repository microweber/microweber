<?php

/*

type: layout

name: Features 3 - Media & Feature List

position: 3

categories: Features

*/

?>

@php
    $items = [
        ['icon' => 'mw-micon-Speed-Fast',       'title' => 'Lightning performance', 'text' => 'Optimised caching and a global CDN keep every page loading in under a second.'],
        ['icon' => 'mw-micon-Shield-Protected',  'title' => 'Built-in security',     'text' => 'Free SSL, automatic backups and continuous malware scanning on every plan.'],
        ['icon' => 'mw-micon-Database-SQL',       'title' => 'Reliable storage',      'text' => 'Redundant NVMe drives keep your data safe with zero-downtime failover.'],
        ['icon' => 'mw-micon-Headphones-Support', 'title' => '24/7 expert support',   'text' => 'Talk to a real engineer any time — average response time under three minutes.'],
    ];
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section features-skin-3"
    field-name="layout-features-skin-3"
>
    <x-row class="align-items-center g-5">
        <x-col size="12" size-lg="6">
            <div class="ratio ratio-4x3 bg-light rounded"></div>
        </x-col>

        <x-col size="12" size-lg="6">
            <div class="safe-mode mb-4">
                <x-section-heading tag="h2" subtitle="Everything you need to build, launch and grow — in a single platform." class="display-6 fw-bold">Powerful features, zero hassle</x-section-heading>
            </div>

            @foreach($items as $item)
                <div class="d-flex align-items-start mb-4 cloneable element safe-mode background-color-element">
                    <i class="{{ $item['icon'] }} fs-2 text-primary me-3 flex-shrink-0 safe-element no-typing"></i>
                    <div>
                        <h5 class="fw-semibold mb-1" data-mwplaceholder="Enter title here">{{ $item['title'] }}</h5>
                        <p class="text-muted small mb-0 regular-mode" data-mwplaceholder="Enter text here">{{ $item['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </x-col>
    </x-row>
</x-layout-section>
