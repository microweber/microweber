<?php
/*
type: layout
name: Price Lists 2 - Monthly vs yearly
position: 2
categories: Price Lists
*/
?>
@php
    $plans = [
        ['name' => 'Monthly', 'price' => '29', 'period' => '/mo',   'features' => ['10 websites','100 GB storage','Priority support','Cancel anytime'], 'featured' => false],
        ['name' => 'Yearly',  'price' => '290','period' => '/year', 'features' => ['10 websites','100 GB storage','Priority support','2 months free'],  'featured' => true],
    ];
    $sectionId = $params['id'] ?? '';
@endphp
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section price-lists-skin-2"
    field-name="layout-price-lists-skin-2"
>
    <x-section-heading tag="h2" subtitle="Pay monthly or save with an annual plan.">
        <span data-mwplaceholder="Enter title here">Flexible billing</span>
    </x-section-heading>

    <div class="row justify-content-center g-4">
        @foreach($plans as $plan)
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card h-100 {{ $plan['featured'] ? 'border-primary shadow' : 'border-0 shadow-sm' }}">
                    <div class="card-body d-flex flex-column p-4 p-lg-5">
                        @if($plan['featured'])
                            <span class="badge bg-primary rounded-pill mb-3 align-self-start" data-mwplaceholder="Enter badge text">Most popular</span>
                        @endif
                        <h3 class="card-title fw-bold mb-3" data-mwplaceholder="Enter plan name">{{ $plan['name'] }}</h3>
                        <div class="mb-4">
                            <span class="display-5 fw-bold" data-mwplaceholder="Enter price">${{ $plan['price'] }}</span>
                            <span class="text-muted" data-mwplaceholder="Enter period">{{ $plan['period'] }}</span>
                        </div>
                        <ul class="list-unstyled mb-4 flex-grow-1">
                            @foreach($plan['features'] as $feature)
                                <li class="mb-2">
                                    <i class="mw-micon-Check text-success me-2"></i>
                                    <span data-mwplaceholder="Enter feature">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-auto">
                            <module type="btn" id="{{ $sectionId }}-plan-{{ $loop->index }}" button_style="btn-primary" button_size="px-4" text="Choose plan"/>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-layout-section>
