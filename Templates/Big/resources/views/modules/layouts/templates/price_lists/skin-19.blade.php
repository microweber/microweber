@php
    /*

    type: layout

    name: Price Lists 19

    position: 22

    categories: Price Lists

    */
@endphp

@php
    if (!isset($classes['padding_top'])) {
        $classes['padding_top'] = 'py-5'; // Default padding
    }
    if (!isset($classes['padding_bottom'])) {
        $classes['padding_bottom'] = 'py-5'; // Default padding
    }

    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';

    // Default setting for the "Most Popular" badge and highlight
    // This will apply independently within each tab (yearly/monthly)
    $popular_plan_index = 1; // 0 = first, 1 = second (middle), 2 = third
@endphp


    <style>

        .price-list-19-tabs { background-color: #121212; }


        .price-list-19-tabs .pricing-card-19 {
            background-color: #252525;
            border-radius: 10px;
            border: 1px solid #252525;
            transition: transform 0.3s ease, border-color 0.3s ease;
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .price-list-19-tabs .pricing-card-19.is-popular {
            border-color: #4a4a4a !important;
            transform: scale(1.05);
            z-index: 10;
            position: relative;
        }

        .price-list-19-tabs .row > .has-popular-card {
            padding-top: 1rem;
            padding-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .price-list-19-tabs .badge-popular {
            background-color: #ffffff;
            color: #000000;
            font-size: 0.75em;
            border-radius: 5px;
            padding: 4px 10px;
            top: -12px;
            right: 20px;
            position: absolute;
            z-index: 11;
        }

        .price-list-19-tabs .pricing-card-19 .price {
            color: #ffffff;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        .price-list-19-tabs .pricing-card-19 .plan-name {
            color: #e0e0e0;
            font-size: 1rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        .price-list-19-tabs .pricing-card-19 .card-content {
            flex-grow: 1;
        }

        .price-list-19-tabs .pricing-card-19 .feature-list {
            color: #a0a0a0;
            font-size: 0.9em;
            padding-left: 1.5rem;
            margin-top: 1.5rem;
            list-style: none;
        }
        .price-list-19-tabs .pricing-card-19 .feature-list li {
            margin-bottom: 0.75rem;
            position: relative;
        }
        .price-list-19-tabs .pricing-card-19 .feature-list li::before {
            content: "•";
            color: #a0a0a0;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1.2em;
            position: absolute;
            left: 0;
        }

        .price-list-19-tabs .pricing-card-19 .mw-module-btn .btn-light {
            background-color: #ffffff;
            color: #000000;
            border-color: #ffffff;
            border-radius: 6px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
        }
        .price-list-19-tabs .pricing-card-19 .mw-module-btn .btn-light:hover {
            background-color: #f0f0f0;
            border-color: #f0f0f0;
            color: #000000;
        }

        .price-list-19-tabs .billing-toggle-nav {
            background-color: #303030;
            display: inline-flex;
            border-radius: 50rem;
        }
        .price-list-19-tabs .billing-toggle-nav .nav-link {
            color: #a0a0a0;
            background-color: transparent;
            border: none;
            padding: 8px 20px;
            font-size: 0.9em;
            transition: background-color 0.2s ease, color 0.2s ease;
            border-radius: 50rem;
            z-index: 1;
        }
        /* Style based on Bootstrap's active class */
        .price-list-19-tabs .billing-toggle-nav .nav-link.active {
            color: #121212;
            background-color: #ffffff;
            z-index: 2;
        }
        /* Hide save badge by default */
        .price-list-19-tabs .badge-save {
            display: none;
            font-size: 0.7em;
            vertical-align: middle;
            margin-left: 8px;
            padding: 3px 6px;
            background-color: #5cb85c;
            color: #ffffff;
        }
        /* Show save badge only when the yearly tab is active */
        .price-list-19-tabs .billing-toggle-nav .nav-link.active[data-bs-target*="Yearly"] .badge-save {
            display: inline-block; /* Or inline, depending on desired flow */
        }



    </style>

    <section class="section price-list-19-tabs {{ $layout_classes }}">

        <module type="background" data-background-color="#1a1a1a" id="background-layout--{{ $params['id'] }}"/>

        <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] }}-top"/>

        <div class="container mw-layout-container edit" field="layout-price-list-19-tabs-{{ $params['id'] }}" rel="module">

            <div class="text-center text-light mb-7">
                <h3 style="color: #fff;">Pricing - Why to buy/How it helps</h3>
                <h4 style="color: #fff;">.................................</h4>
                <h4 style="color: #fff;">....................</h4>
            </div>

            <!-- Billing Toggle -->
            <div class="d-flex justify-content-center mb-5">
                <nav class="nav nav-pills billing-toggle-nav" id="billingToggleNavTabs-{{ $params['id'] }}">
                    {{-- Ensure unique href/aria-controls FOR TABS --}}
                    {{-- data-bs-target now points to the ID of the tab pane --}}
                    <a class="nav-link active" id="yearly-tab-link-{{ $params['id'] }}" data-bs-toggle="tab" data-bs-target="#pricingCardsYearly-{{ $params['id'] }}" href="#pricingCardsYearly-{{ $params['id'] }}" type="button" role="tab" aria-controls="pricingCardsYearly-{{ $params['id'] }}" aria-selected="true">
                        Bill yearly
                        <span class="badge badge-save rounded-pill">Save 30%</span> {{-- Badge visibility controlled by CSS --}}
                    </a>
                    <a class="nav-link" id="monthly-tab-link-{{ $params['id'] }}" data-bs-toggle="tab" data-bs-target="#pricingCardsMonthly-{{ $params['id'] }}" href="#pricingCardsMonthly-{{ $params['id'] }}" type="button" role="tab" aria-controls="pricingCardsMonthly-{{ $params['id'] }}" aria-selected="false">
                        Monthly
                    </a>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="pricingTabContent-{{ $params['id'] }}">

                <!-- Yearly Pricing Tab Pane -->
                <div class="tab-pane fade show active" id="pricingCardsYearly-{{ $params['id'] }}" role="tabpanel" aria-labelledby="yearly-tab-link-{{ $params['id'] }}">
                    <div class="row justify-content-center g-4 align-items-end">
                        {{-- Card 1: Starter (Yearly) --}}
                        <div class="col-12 col-md-6 col-lg-4 {{ $popular_plan_index === 0 ? 'has-popular-card' : '' }}">
                            <div class="pricing-card-19 p-4 p-lg-5 text-start {{ $popular_plan_index === 0 ? 'is-popular' : '' }} background-color-element element position-relative">
                                @if($popular_plan_index === 0)
                                    <span class="badge badge-popular position-absolute fw-bold background-color-element element">Most Popular</span>
                                @endif
                                <div class="card-content">
                                    {{-- Make Plan Name editable --}}
                                    <h5 class="plan-name" field="plan-1-name-yearly-{{ $params['id'] }}" rel="module">Starter</h5>
                                    {{-- Make Price editable --}}
                                    <div class="price" field="plan-1-price-yearly-{{ $params['id'] }}" rel="module">$960/year</div>
                                    {{-- Keep button module separate --}}
                                    <module type="btn" id="btn-plan-1-yearly-{{ $params['id'] }}" button_size="btn-md w-100" text="CTA Yearly" class="mb-4"/>
                                    {{-- Make features editable (using wysiwyg for list) --}}
                                    <div class="feature-list" field="plan-1-features-yearly-{{ $params['id'] }}" rel="module">
                                        <ul class="list-unstyled">
                                            <li>Yearly Feature 1</li>
                                            <li>Yearly Feature 2</li>
                                            <li>Yearly Feature 3</li>
                                            <li>Yearly Feature 4</li>
                                            <li>Yearly Feature 5</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card 2: Pro (Yearly) --}}
                        <div class="col-12 col-md-6 col-lg-4 {{ $popular_plan_index === 1 ? 'has-popular-card' : '' }}">
                            <div class="pricing-card-19 p-4 p-lg-5 text-start {{ $popular_plan_index === 1 ? 'is-popular' : '' }} background-color-element element position-relative">
                                @if($popular_plan_index === 1)
                                    <span class="badge badge-popular position-absolute fw-bold background-color-element element">Most Popular</span>
                                @endif
                                <div class="card-content">
                                    <h5 class="plan-name" field="plan-2-name-yearly-{{ $params['id'] }}" rel="module">Pro</h5>
                                    <div class="price" field="plan-2-price-yearly-{{ $params['id'] }}" rel="module">$1920/year</div>
                                    <module type="btn" id="btn-plan-2-yearly-{{ $params['id'] }}" button_size="btn-md w-100" text="CTA Yearly" class="mb-4"/>
                                    <div class="feature-list" field="plan-2-features-yearly-{{ $params['id'] }}" rel="module">
                                        <ul class="list-unstyled">
                                            <li class="fw-medium" style="color: #d0d0d0;">Everything in Starter yearly plus:</li>
                                            <li>Yearly Feature 1</li>
                                            <li>Yearly Feature 2</li>
                                            <li>Yearly Feature 3</li>
                                            <li>Yearly Feature 4</li>
                                            <li>Yearly Feature 5</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card 3: Advanced (Yearly) --}}
                        <div class="col-12 col-md-6 col-lg-4 {{ $popular_plan_index === 2 ? 'has-popular-card' : '' }}">
                            <div class="pricing-card-19 p-4 p-lg-5 text-start {{ $popular_plan_index === 2 ? 'is-popular' : '' }} background-color-element element position-relative">
                                @if($popular_plan_index === 2)
                                    <span class="badge badge-popular position-absolute fw-bold background-color-element element">Most Popular</span>
                                @endif
                                <div class="card-content">
                                    <h5 class="plan-name" field="plan-3-name-yearly-{{ $params['id'] }}" rel="module">Advanced</h5>
                                    <div class="price" field="plan-3-price-yearly-{{ $params['id'] }}" rel="module">$2880/year</div>
                                    <module type="btn" id="btn-plan-3-yearly-{{ $params['id'] }}" button_size="btn-md w-100" text="CTA Yearly" class="mb-4"/>
                                    <div class="feature-list" field="plan-3-features-yearly-{{ $params['id'] }}" rel="module">
                                        <ul class="list-unstyled">
                                            <li class="fw-medium" style="color: #d0d0d0;">Everything in Pro yearly plus:</li>
                                            <li>Yearly Feature 1</li>
                                            <li>Yearly Feature 2</li>
                                            <li>Yearly Feature 3</li>
                                            <li>Yearly Feature 4</li>
                                            <li>Yearly Feature 5</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Pricing Tab Pane -->
                <div class="tab-pane fade" id="pricingCardsMonthly-{{ $params['id'] }}" role="tabpanel" aria-labelledby="monthly-tab-link-{{ $params['id'] }}">
                    <div class="row justify-content-center g-4 align-items-end">
                        {{-- Card 1: Starter (Monthly) --}}
                        <div class="col-12 col-md-6 col-lg-4 {{ $popular_plan_index === 0 ? 'has-popular-card' : '' }}">
                            <div class="pricing-card-19 p-4 p-lg-5 text-start {{ $popular_plan_index === 0 ? 'is-popular' : '' }} background-color-element element position-relative">
                                @if($popular_plan_index === 0)
                                    <span class="badge badge-popular position-absolute fw-bold background-color-element element">Most Popular</span>
                                @endif
                                <div class="card-content">
                                    <h5 class="plan-name" field="plan-1-name-monthly-{{ $params['id'] }}" rel="module">Starter</h5>
                                    <div class="price" field="plan-1-price-monthly-{{ $params['id'] }}" rel="module">$100/month</div>
                                    <module type="btn" id="btn-plan-1-monthly-{{ $params['id'] }}" button_size="btn-md w-100" text="CTA Monthly" class="mb-4"/>
                                    <div class="feature-list" field="plan-1-features-monthly-{{ $params['id'] }}" rel="module">
                                        <ul class="list-unstyled">
                                            <li>Monthly Feature 1</li>
                                            <li>Monthly Feature 2</li>
                                            <li>Monthly Feature 3</li>
                                            <li>Monthly Feature 4</li>
                                            <li>Monthly Feature 5</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card 2: Pro (Monthly) --}}
                        <div class="col-12 col-md-6 col-lg-4 {{ $popular_plan_index === 1 ? 'has-popular-card' : '' }}">
                            <div class="pricing-card-19 p-4 p-lg-5 text-start {{ $popular_plan_index === 1 ? 'is-popular' : '' }} background-color-element element position-relative">
                                @if($popular_plan_index === 1)
                                    <span class="badge badge-popular position-absolute fw-bold background-color-element element">Most Popular</span>
                                @endif
                                <div class="card-content">
                                    <h5 class="plan-name" field="plan-2-name-monthly-{{ $params['id'] }}" rel="module">Pro</h5>
                                    <div class="price" field="plan-2-price-monthly-{{ $params['id'] }}" rel="module">$200/month</div>
                                    <module type="btn" id="btn-plan-2-monthly-{{ $params['id'] }}" button_size="btn-md w-100" text="CTA Monthly" class="mb-4"/>
                                    <div class="feature-list" field="plan-2-features-monthly-{{ $params['id'] }}" rel="module">
                                        <ul class="list-unstyled">
                                            <li class="fw-medium" style="color: #d0d0d0;">Everything in Starter monthly plus:</li>
                                            <li>Monthly Feature 1</li>
                                            <li>Monthly Feature 2</li>
                                            <li>Monthly Feature 3</li>
                                            <li>Monthly Feature 4</li>
                                            <li>Monthly Feature 5</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card 3: Advanced (Monthly) --}}
                        <div class="col-12 col-md-6 col-lg-4 {{ $popular_plan_index === 2 ? 'has-popular-card' : '' }}">
                            <div class="pricing-card-19 p-4 p-lg-5 text-start {{ $popular_plan_index === 2 ? 'is-popular' : '' }} background-color-element element position-relative">
                                @if($popular_plan_index === 2)
                                    <span class="badge badge-popular position-absolute fw-bold background-color-element element">Most Popular</span>
                                @endif
                                <div class="card-content">
                                    <h5 class="plan-name" field="plan-3-name-monthly-{{ $params['id'] }}" rel="module">Advanced</h5>
                                    <div class="price" field="plan-3-price-monthly-{{ $params['id'] }}" rel="module">$300/month</div>
                                    <module type="btn" id="btn-plan-3-monthly-{{ $params['id'] }}" button_size="btn-md w-100" text="CTA Monthly" class="mb-4"/>
                                    <div class="feature-list" field="plan-3-features-monthly-{{ $params['id'] }}" rel="module">
                                        <ul class="list-unstyled">
                                            <li class="fw-medium" style="color: #d0d0d0;">Everything in Pro monthly plus:</li>
                                            <li>Monthly Feature 1</li>
                                            <li>Monthly Feature 2</li>
                                            <li>Monthly Feature 3</li>
                                            <li>Monthly Feature 4</li>
                                            <li>Monthly Feature 5</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End Tab Content -->

        </div> {{-- End Container --}}

        <module type="spacer" height="120px" id="spacer-layout--{{ $params['id'] }}-bottom"/>
    </section>
