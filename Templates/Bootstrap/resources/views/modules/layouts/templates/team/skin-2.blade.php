<?php

/*

type: layout

name: Team 2 - Profile Cards

position: 2

categories: Team

*/

?>

@php
    $members = [
        ['name' => 'Marcus Bennett', 'role' => 'Chief Technology Officer', 'text' => 'Fifteen years shipping resilient systems at scale, now leading our engineering roadmap.'],
        ['name' => 'Elena Petrova',  'role' => 'Head of Marketing',        'text' => 'Storyteller at heart — connecting the product to the people who need it most.'],
        ['name' => 'Jordan Blake',   'role' => 'Customer Success Lead',     'text' => 'Your first point of contact and biggest advocate, from onboarding to renewal.'],
    ];
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section team-skin-2"
    field-name="layout-team-skin-2"
>
    <x-row class="text-center safe-mode">
        <x-col size="12" size-lg="8" size-xl="8" size-xxl="8" class="mx-auto">
            <div class="regular-mode">
                <x-section-heading tag="h2" subtitle="A small, senior team with a lot of shipped software behind us." class="display-6 fw-bold">Leadership</x-section-heading>
            </div>
        </x-col>
    </x-row>

    <x-row class="row-cols-1 row-cols-md-3 g-4 mt-3">
        @foreach($members as $m)
            <div class="col cloneable element safe-mode">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="ratio ratio-1x1 bg-light"></div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold mb-1" data-mwplaceholder="Enter title here">{{ $m['name'] }}</h5>
                        <p class="text-muted small mb-2" data-mwplaceholder="Enter text here">{{ $m['role'] }}</p>
                        <p class="card-text small regular-mode" data-mwplaceholder="Enter text here">{{ $m['text'] }}</p>
                        <div class="d-flex justify-content-center gap-3 fs-5 mt-3">
                            <a href="#" class="text-muted" aria-label="Linkedin"><i class="mw-micon-Linkedin"></i></a>
                            <a href="#" class="text-muted" aria-label="Twitter"><i class="mw-micon-Twitter"></i></a>
                            <a href="#" class="text-muted" aria-label="Email"><i class="mw-micon-Mail"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </x-row>
</x-layout-section>
