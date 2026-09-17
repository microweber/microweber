<?php
/*
type: layout
name: Price Lists 3 - Single wide plan
position: 3
categories: Price Lists
*/
?>
@php
    $plan = [
        'name' => 'Pro', 'price' => '29', 'period' => '/mo',
        'features' => ['10 websites','100 GB storage','Priority support','Free SSL','Daily backups','Custom domain'],
        'featured' => true,
    ];
    $sectionId = $params['id'] ?? '';
@endphp
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section price-lists-skin-3"
    field-name="layout-price-lists-skin-3"
>
    <div class="card border-primary shadow">
        <div class="card-body p-4 p-lg-5">
            <div class="row align-items-center g-4">
                <div class="col-12 col-lg-5 text-center text-lg-start border-lg-end">
                    <span class="badge bg-primary rounded-pill mb-3" data-mwplaceholder="Enter badge text">Most popular</span>
                    <h3 class="fw-bold mb-3" data-mwplaceholder="Enter plan name">{{ $plan['name'] }}</h3>
                    <div class="mb-4">
                        <span class="display-3 fw-bold" data-mwplaceholder="Enter price">${{ $plan['price'] }}</span>
                        <span class="text-muted" data-mwplaceholder="Enter period">{{ $plan['period'] }}</span>
                    </div>
                    <module type="btn" id="{{ $sectionId }}-plan" button_style="btn-primary" button_size="px-4" text="Choose plan"/>
                </div>
                <div class="col-12 col-lg-7">
                    <ul class="list-unstyled row row-cols-1 row-cols-sm-2 g-2 mb-0">
                        @foreach($plan['features'] as $feature)
                            <li class="col">
                                <i class="mw-micon-Check text-success me-2"></i>
                                <span data-mwplaceholder="Enter feature">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layout-section>
