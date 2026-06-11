<?php

/*

type: layout

name: Features 2 - Advantages Grid

position: 2

categories: Features

*/

?>

@php
    $advantageItems = [
        ['icon' => 'mw-micon-Speed-Fast',          'title' => 'Blazing-fast NVMe',   'text' => 'NVMe storage and HTTP/3 deliver up to 20x faster load times than traditional hosting.'],
        ['icon' => 'mw-micon-Shield-Protected',     'title' => 'Enterprise security', 'text' => 'Free SSL, daily backups, DDoS protection and malware scanning — included on every plan.'],
        ['icon' => 'mw-micon-Headphones-Support',   'title' => 'Human 24/7 support',  'text' => 'Reach a real engineer by chat, email or phone — average response time under 3 minutes.'],
        ['icon' => 'mw-micon-Certified-Badge',      'title' => '99.9% uptime SLA',    'text' => 'Redundant infrastructure across geographically distributed datacenters with automatic failover.'],
        ['icon' => 'mw-micon-Globe-Earth',           'title' => 'Global CDN',          'text' => 'Serve your site from 200+ edge locations worldwide so visitors always get the closest copy.'],
        ['icon' => 'mw-micon-Database-SQL',          'title' => 'One-click installs',  'text' => 'Spin up Microweber, Drupal, Joomla and 100+ other apps in seconds.'],
        ['icon' => 'mw-micon-CreditCard-Payment',   'title' => 'No hidden fees',      'text' => 'Transparent pricing — the price you see is the price you pay. Cancel any time.'],
        ['icon' => 'mw-micon-ArrowUp-Growth',        'title' => 'Scale on demand',     'text' => 'Upgrade with a single click when your traffic spikes — no downtime, no migration.'],
    ];
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section features-skin-2-advantages"
    field-name="layout-features-skin-2"
>
    <x-row class="text-center safe-mode">
        <x-col size="12" size-lg="8" size-xl="8" size-xxl="8" class="mx-auto">
            <div class="regular-mode">
                <x-section-heading tag="h2" subtitle="Everything you need to launch and scale — with no hidden costs and no lock-in." class="display-5 fw-bold">Why choose our hosting</x-section-heading>
            </div>
        </x-col>
    </x-row>

    <x-row class="row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mt-3">
        @foreach($advantageItems as $item)
            <div class="col cloneable element text-center safe-mode background-color-element">
                <div class="p-3">
                    <i class="features-skin-2-icons mb-3 safe-element no-typing {{ $item['icon'] }} d-block fs-1 text-primary"></i>
                    <h5 class="fw-semibold" data-mwplaceholder="Enter title here">{{ $item['title'] }}</h5>
                    <p class="text-muted small regular-mode" data-mwplaceholder="Enter text here">{{ $item['text'] }}</p>
                </div>
            </div>
        @endforeach
    </x-row>
</x-layout-section>
