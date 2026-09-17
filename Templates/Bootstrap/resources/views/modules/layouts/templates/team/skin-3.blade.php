<?php

/*

type: layout

name: Team 3 - Featured & Grid

position: 3

categories: Team

*/

?>

@php
    $featured = [
        'name' => 'Nadia Hassan',
        'role' => 'Founder & Managing Director',
        'text' => 'Nadia started the company from a spare bedroom with a simple belief: great tools should feel invisible. A decade on, she still reviews every release and answers customer emails personally.',
    ];
    $members = [
        ['name' => 'Tomás Rivera',  'role' => 'VP of Engineering'],
        ['name' => 'Grace Okoro',   'role' => 'Head of Design'],
        ['name' => 'Liam Walsh',    'role' => 'Operations Manager'],
        ['name' => 'Yuki Tanaka',   'role' => 'Data & Analytics'],
    ];
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section team-skin-3"
    field-name="layout-team-skin-3"
>
    <x-row class="text-center safe-mode">
        <x-col size="12" size-lg="8" size-xl="8" size-xxl="8" class="mx-auto">
            <div class="regular-mode">
                <x-section-heading tag="h2" subtitle="One founder, one focused crew — meet the people you'll be working with." class="display-6 fw-bold">Our team</x-section-heading>
            </div>
        </x-col>
    </x-row>

    <x-row class="g-4 mt-2 align-items-stretch">
        <x-col size="12" size-md="5">
            <div class="p-4 h-100 bg-light rounded-3 text-center safe-mode">
                <div class="ratio ratio-1x1 rounded-circle bg-white mx-auto mb-3" style="max-width:180px;"></div>
                <h4 class="fw-bold mb-1" data-mwplaceholder="Enter title here">{{ $featured['name'] }}</h4>
                <p class="text-muted mb-3" data-mwplaceholder="Enter text here">{{ $featured['role'] }}</p>
                <p class="regular-mode" data-mwplaceholder="Enter text here">{{ $featured['text'] }}</p>
                <div class="d-flex justify-content-center gap-3 fs-5 mt-3">
                    <a href="#" class="text-muted" aria-label="Linkedin"><i class="mw-micon-Linkedin"></i></a>
                    <a href="#" class="text-muted" aria-label="Twitter"><i class="mw-micon-Twitter"></i></a>
                    <a href="#" class="text-muted" aria-label="Website"><i class="mw-micon-Globe-Earth"></i></a>
                </div>
            </div>
        </x-col>

        <x-col size="12" size-md="7">
            <x-row class="row-cols-1 row-cols-sm-2 g-4 h-100">
                @foreach($members as $m)
                    <div class="col cloneable element safe-mode">
                        <div class="p-3 h-100 border rounded-3 text-center">
                            <div class="ratio ratio-1x1 rounded-circle bg-light mx-auto mb-3" style="max-width:96px;"></div>
                            <h5 class="fw-semibold mb-1" data-mwplaceholder="Enter title here">{{ $m['name'] }}</h5>
                            <p class="text-muted small mb-0" data-mwplaceholder="Enter text here">{{ $m['role'] }}</p>
                        </div>
                    </div>
                @endforeach
            </x-row>
        </x-col>
    </x-row>
</x-layout-section>
