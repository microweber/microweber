{{--
type: layout

name: Price Lists 10

position: 10

categories: Price Lists
--}}

<style>
    .price-lists-10 .card {
        border-color: var(--mw-primary-color);
        border-radius: 0;
    }

    .price-lists-10 .card:hover {
        background-color: var(--mw-primary-color);
    }

    .price-lists-10 .card li, .price-lists-10 .card h1, .price-lists-10 .card p, .price-lists-10 .card .h1, .price-lists-10 .card .h3 {
        color: var(--mw-primary-color);
    }

    .price-lists-10 .card:hover li, .price-lists-10 .card:hover h1, .price-lists-10 .card:hover p, .price-lists-10 .card:hover .h1, .price-lists-10 .card:hover .h3, .price-lists-10 .card:hover small {
        color: #ffffff!important;
    }

    .price-lists-10 .btn.btn-primary {
        background-color: var(--mw-primary-color)!important;
        color: #ffffff!important;
        padding: 10px 30px!important;
    }

    .price-list-9-hr {
        border-color: var(--mw-primary-color)!important;
        border-top: 3px solid var(--mw-primary-color)!important;
        opacity: 1;
        margin-top: 40px;
        margin-bottom: 0;
    }

    .price-lists-10 .card:hover .price-list-9-hr {
        border-color: #ffffff!important;
        border-top: 3px solid #ffffff!important;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="price-lists-10 py-0 section"
    field-name="layout-price-lists-skin-10"
    default-padding-top="p-t-70"
    default-padding-bottom="p-b-70"
    container-class="mw-layout-container no-element container-fluid edit safe-mode"
>
    <x-row class="mx-auto justify-content-center">
                <div class="col-xl-12 d-flex flex-wrap justify-content-center">
                    <div class="col-xxl-3 col-md-6 col-12 cloneable element safe-mode">
                        <div class="card h-100 px-3 my-3 allow-select regular-mode background-color-element element">
                            <div class="card-body mt-3 mx-1 text-center">
                                <span class="h3">$</span><span class="h1">24</span>
                                <p class="my-4">Per Month</p>
                                <small class="my-3" style="line-height: 1.6; color: #737272;">In This Case, You buy a subscription to use the basic features of The radio features of The radio. You buy a subscription</small>
                                <hr class="price-list-9-hr">
                            </div>
                            <x-row class="justify-content-center mx-auto">
                                <ul class="list-unstyled text-start">
                                    <li class="my-4"><i class="mw-micon-Circular-Point me-1"></i> Listen radio Podcasts</li>
                                    <li class="my-4"><i class="mw-micon-Circular-Point me-1"></i> Customer</li>
                                    <li class="my-4"><i class="mw-micon-solid-Circular-Point me-1"></i> Listen Premium Podcasts</li>
                                    <li class="my-4"><i class="mw-micon-solid-Circular-Point me-1"></i> Publish Podcasts</li>
                                </ul>
                            </x-row>
                            <div class="mb-4">
                                <module style="text-align: center;" class="safe-element" type="btn" button_text="Basic Plan" />
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-md-6 col-12 cloneable element safe-mode">
                        <div class="card h-100 px-3 my-3 allow-select regular-mode background-color-element element">
                            <div class="card-body mt-3 mx-1 text-center">
                                <span class="h3">$</span><span class="h1">24</span>
                                <p class="my-4">Per Month</p>
                                <small class="my-3" style="line-height: 1.6; color: #737272;">In This Case, addition to the basic In This Case, You buy a subscription to use the basic features You buy a subscription of The radio.</small>
                                <hr class="price-list-9-hr">
                            </div>
                            <x-row class="justify-content-center mx-auto">
                                <ul class="list-unstyled text-start">
                                    <li class="my-4"><i class="mw-micon-Circular-Point me-1"></i>Listen radio Podcasts</li>
                                    <li class="my-4"><i class="mw-micon-Circular-Point me-1"></i>customer</li>
                                    <li class="my-4"><i class="mw-micon-solid-Circular-Point me-1"></i>Listen Premium Podcasts</li>
                                    <li class="my-4"><i class="mw-micon-solid-Circular-Point me-1"></i>Publish Podcasts</li>
                                </ul>
                            </x-row>
                            <div class="mb-4">
                                <module style="text-align: center;" class="safe-element" type="btn" button_text="Premium Plan" />
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-md-6 col-12 cloneable element safe-mode">
                        <div class="card h-100 px-3 my-3 allow-select regular-mode background-color-element element">
                            <div class="card-body mt-3 mx-1 text-center">
                                <span class="h3">$</span><span class="h1">100</span>
                                <p class="my-4">Per Month</p>
                                <small class="my-3" style="line-height: 1.6; color: #737272;">In This Case, You buy a subscription to use the basic features of The radio You buy a subscription features of The radio.</small>
                                <hr class="price-list-9-hr">
                            </div>
                            <x-row class="justify-content-center mx-auto">
                                <ul class="list-unstyled text-start">
                                    <li class="my-4"><i class="mw-micon-Circular-Point me-1"></i>Listen radio Podcasts</li>
                                    <li class="my-4"><i class="mw-micon-Circular-Point me-1"></i>customer</li>
                                    <li class="my-4"><i class="mw-micon-solid-Circular-Point me-1"></i>Listen Premium Podcasts</li>
                                    <li class="my-4"><i class="mw-micon-solid-Circular-Point me-1"></i>Publish Podcasts</li>
                                </ul>
                            </x-row>
                            <div class="mb-4">
                                <module style="text-align: center;" class="safe-element" type="btn" button_text="Popular" />
                            </div>
                        </div>
                    </div>
                </div>
            </x-row>
</x-layout-section>
