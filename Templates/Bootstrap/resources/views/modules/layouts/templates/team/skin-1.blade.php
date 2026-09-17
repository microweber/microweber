<?php

/*

type: layout

name: Team 1 - Member Cards

position: 1

categories: Team

*/

?>

@php
    $members = [
        ['name' => 'Alex Morgan',    'role' => 'Founder & CEO',      'text' => 'Sets the vision and keeps the team pointed at what matters.'],
        ['name' => 'Priya Nair',     'role' => 'Head of Product',    'text' => 'Turns big ideas into shippable, delightful features.'],
        ['name' => 'Daniel Foster',  'role' => 'Lead Engineer',      'text' => 'Builds the platform that everything else runs on.'],
        ['name' => 'Sofia Rossi',    'role' => 'Design Director',    'text' => 'Makes every screen feel effortless and on-brand.'],
    ];
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section team-skin-1"
    field-name="layout-team-skin-1"
>
    <x-row class="text-center safe-mode">
        <x-col size="12" size-lg="8" size-xl="8" size-xxl="8" class="mx-auto">
            <div class="regular-mode">
                <x-section-heading tag="h2" subtitle="The people behind the work — friendly faces you'll get to know." class="display-6 fw-bold">Meet the team</x-section-heading>
            </div>
        </x-col>
    </x-row>

    <x-row class="row-cols-2 row-cols-lg-4 g-4 mt-3 text-center">
        @foreach($members as $m)
            <div class="col cloneable element safe-mode">
                <div class="p-3">
                    <div class="ratio ratio-1x1 rounded-circle bg-light mx-auto mb-3" style="max-width:120px;"></div>
                    <h5 class="fw-semibold mb-1" data-mwplaceholder="Enter title here">{{ $m['name'] }}</h5>
                    <p class="text-muted small mb-2" data-mwplaceholder="Enter text here">{{ $m['role'] }}</p>
                    <div class="d-flex justify-content-center gap-3 fs-5">
                        <a href="#" class="text-muted" aria-label="Facebook"><i class="mw-micon-Facebook"></i></a>
                        <a href="#" class="text-muted" aria-label="Twitter"><i class="mw-micon-Twitter"></i></a>
                        <a href="#" class="text-muted" aria-label="Linkedin"><i class="mw-micon-Linkedin"></i></a>
                    </div>
                </div>
            </div>
        @endforeach
    </x-row>
</x-layout-section>
