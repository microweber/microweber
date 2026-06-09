<?php

/*

type: layout

name: Pricing 2 - Hosting Plans

position: 2

categories: Pricing

*/

?>

@php
    $pricingPlans = [
        [
            'name'        => 'Start',
            'subtitle'    => 'For a first personal site',
            'price'       => '$2.99',
            'highlighted' => false,
            'headerBg'    => 'bg-transparent',
            'headerText'  => '',
            'features'    => ['1 website', '10 GB SSD storage', 'Unmetered traffic', 'Free SSL certificate', 'Daily backups'],
            'btnId'       => $params['id'] . '-btn-1',
            'btnStyle'    => 'w-100 btn btn-outline-primary',
            'btnText'     => 'Choose Start',
        ],
        [
            'name'        => 'Plus',
            'subtitle'    => 'For growing projects',
            'price'       => '$5.99',
            'highlighted' => true,
            'headerBg'    => 'bg-primary text-white border-primary',
            'headerText'  => '',
            'badge'       => 'Most popular',
            'features'    => ['Unlimited websites', '50 GB SSD storage', 'Unmetered traffic', 'Free SSL + CDN', 'Priority 24/7 support'],
            'btnId'       => $params['id'] . '-btn-2',
            'btnStyle'    => 'w-100 btn btn-primary',
            'btnText'     => 'Choose Plus',
        ],
        [
            'name'        => 'Turbo',
            'subtitle'    => 'Performance-tuned',
            'price'       => '$9.99',
            'highlighted' => false,
            'headerBg'    => 'bg-transparent',
            'headerText'  => '',
            'features'    => ['Unlimited websites', '150 GB NVMe storage', '4x CPU &amp; RAM', 'Dedicated IP', 'Staging environment'],
            'btnId'       => $params['id'] . '-btn-3',
            'btnStyle'    => 'w-100 btn btn-outline-primary',
            'btnText'     => 'Choose Turbo',
        ],
        [
            'name'        => 'Business',
            'subtitle'    => 'Teams and agencies',
            'price'       => '$19.99',
            'highlighted' => false,
            'headerBg'    => 'bg-transparent',
            'headerText'  => '',
            'features'    => ['Unlimited websites', '500 GB NVMe storage', '8x CPU &amp; RAM', 'Advanced DDoS protection', 'Dedicated account manager'],
            'btnId'       => $params['id'] . '-btn-4',
            'btnStyle'    => 'w-100 btn btn-outline-primary',
            'btnText'     => 'Choose Business',
        ],
    ];
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section pricing-skin-2"
    field-name="layout-pricing-skin-2"
    :no-drop="true"
    container-class="mw-layout-container py-3"
>
    <div class="pricing-header pb-4 mx-auto text-center" style="max-width: 720px;">
        <h2 class="display-5 fw-bold">Choose your hosting plan</h2>
        <p class="fs-5 text-muted">Fast, secure and scalable — every plan includes a free domain, SSL and 24/7 support. Upgrade or downgrade any time.</p>
    </div>

    <x-row class="row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 text-center">
        @foreach($pricingPlans as $plan)
            {{-- bare .col — row-cols-* needs it; <x-col> would force col-12 and stack --}}
            <div class="col">
                <x-card class="h-100 rounded-3 shadow-sm{{ $plan['highlighted'] ? ' border-primary position-relative' : '' }}">
                    @if(!empty($plan['badge']))
                        <span class="badge bg-primary position-absolute top-0 start-50 translate-middle mt-0 px-3 py-2 rounded-pill text-uppercase">{{ $plan['badge'] }}</span>
                    @endif
                    <x-slot name="header">
                        <div class="py-3 {{ $plan['headerBg'] }}">
                            <h4 class="my-0 {{ $plan['highlighted'] ? 'fw-semibold' : 'fw-normal' }}">{{ $plan['name'] }}</h4>
                            <small class="{{ $plan['highlighted'] ? 'opacity-75' : 'text-muted' }}">{{ $plan['subtitle'] }}</small>
                        </div>
                    </x-slot>
                    <x-slot name="content">
                        <div class="d-flex flex-column">
                            <h1 class="card-title pricing-card-title mb-0">{{ $plan['price'] }}<small class="text-muted fw-light fs-6">/mo</small></h1>
                            <small class="text-muted d-block mb-3">billed annually</small>
                            <ul class="list-unstyled mt-2 mb-4 text-start">
                                @foreach($plan['features'] as $feature)
                                    <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>{!! $feature !!}</li>
                                @endforeach
                            </ul>
                            <div class="mt-auto">
                                <module type="btn" id="{{ $plan['btnId'] }}" button_style="{{ $plan['btnStyle'] }}" button_text="{{ $plan['btnText'] }}"/>
                            </div>
                        </div>
                    </x-slot>
                </x-card>
            </div>
        @endforeach
    </x-row>

    <p class="text-center text-muted mt-4 mb-0"><small>30-day money-back guarantee &middot; Cancel any time &middot; No setup fees</small></p>
</x-layout-section>
