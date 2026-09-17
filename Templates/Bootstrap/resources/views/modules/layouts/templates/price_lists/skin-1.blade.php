<?php
/*
type: layout
name: Price Lists 1 - Three tier cards
position: 1
categories: Price Lists
*/
?>
@php
    $plans = [
        ['name' => 'Starter', 'price' => '9', 'features' => ['1 website','10 GB storage','Email support'], 'featured' => false],
        ['name' => 'Pro',     'price' => '29','features' => ['10 websites','100 GB storage','Priority support','Free SSL'], 'featured' => true],
        ['name' => 'Business','price' => '79','features' => ['Unlimited websites','1 TB storage','24/7 support','SLA'], 'featured' => false],
    ];
    $sectionId = $params['id'] ?? '';
@endphp
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section price-lists-skin-1"
    field-name="layout-price-lists-skin-1"
>
    <x-section-heading tag="h2" subtitle="Simple, transparent pricing that grows with you.">
        <span data-mwplaceholder="Enter title here">Choose your plan</span>
    </x-section-heading>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($plans as $plan)
            <div class="col">
                <div class="card h-100 text-center {{ $plan['featured'] ? 'border-primary shadow' : 'border-0 shadow-sm' }}">
                    <div class="card-body d-flex flex-column p-4">
                        @if($plan['featured'])
                            <span class="badge bg-primary rounded-pill mb-3 align-self-center" data-mwplaceholder="Enter badge text">Most popular</span>
                        @endif
                        <h3 class="card-title fw-bold mb-3" data-mwplaceholder="Enter plan name">{{ $plan['name'] }}</h3>
                        <div class="mb-4">
                            <span class="display-5 fw-bold" data-mwplaceholder="Enter price">${{ $plan['price'] }}</span>
                            <span class="text-muted" data-mwplaceholder="Enter period">/mo</span>
                        </div>
                        <ul class="list-unstyled text-start mb-4 flex-grow-1">
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
