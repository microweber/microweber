@php
    /*

    type: layout

    name: Price Lists 18

    position: 18

    categories: Price Lists

    */
@endphp

@php
    if (!isset($classes['padding_top'])) {
        $classes['padding_top'] = '';
    }
    if (!isset($classes['padding_bottom'])) {
        $classes['padding_bottom'] = '';
    }

    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp


<style>
    .comparison-grid.bg-base { background-color: #1a1a1a; }

    .comparison-grid .row.is-last-row { border-bottom: none; }

    .plan-col {
        background-color: #252525;
        min-height: 65px;
    }
    .category-plan-col d-lg-flex d-none {
        background-color: transparent;
    }


    .category-detail-row {
        border: 1px solid #888888;
        background-color: #303030;
    }

    .plan-header-spacer { min-height: 120px; }
    .feature-cell-spacer { min-height: 30px; }
    .plan-header { gap: 15px; min-height: 90px; }


    .category-badge { background-color: #303030; color: #e0e0e0; }
    .fs-sm { font-size: 0.85em; }


    .feature-name-col {
        color: #888888;
        font-size: 0.9em;
        letter-spacing: 1.5px;
        min-height: 55px;
    }

    .check-cell-col {
        font-size: 1.2em;
        color: #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .check-cell-col .mdi-close-thick {
        color: #e07b7b;
    }

    .rounded-lg {
        border-radius: 10px;
    }


</style>

<section class="section price-list-18 {{ $layout_classes }}">

    <module type="background" data-background-color="#1a1a1a" id="background-layout--{{ $params['id'] }}"/>

    <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="container-fluid mw-layout-container mw-layout-overlay-container edit safe-mode" field="layout-skin-18-{{ $params['id'] }}" rel="module">
        <div class="comparison-grid bg-base background-color-element element rounded-lg overflow-hidden">
            <div class="row g-0 plan-header-row">
                <div class="col-lg-3">
                    <div class="plan-header-spacer">
                        <h5 class="fw-semibold text-light py-4 mb-0 ps-2">Compare our plans</h5>
                    </div>
                </div>
                <div class="col-lg-3 plan-col background-color-element element plan-col-first rounded-lg-top-start p-3">
                    <div class="plan-header w-100 d-flex flex-column align-items-center justify-content-center py-2">
                        <span class="fw-semibold fs-6" style="color: #fff;">Startup</span>

                        <module type="btn" button_style="btn-primary" text="Try for Free"/>

                    </div>
                </div>
                <div class="col-lg-3 plan-col background-color-element element p-3">
                    <div class="plan-header w-100 d-flex flex-column align-items-center justify-content-center py-2">
                        <span class="fw-semibold fs-6" style="color: #fff;">Business</span>
                        <module type="btn" button_style="btn-primary" text="Try for Free"/>
                    </div>
                </div>
                <div class="col-lg-3 plan-col background-color-element element plan-col-last rounded-lg-top-end p-3">
                    <div class="plan-header w-100 d-flex flex-column align-items-center justify-content-center py-2">
                        <span class="fw-semibold fs-6" style="color: #fff;">Custom</span>
                        <module type="btn" button_style="btn-primary" text="Contact Us"/>
                    </div>
                </div>
            </div>

           <div class="cloneable py-0">
               <div class="row g-0 py-3 category-detail-row rounded-lg background-color-element cloneable element align-items-center">
                   <div class="col-lg-3 category-title-col d-flex align-items-center ps-2 pe-2">
                       <span class="category-badge d-inline-block background-color-element element fw-medium py-1 px-3 rounded-lg fs-sm">Category</span>
                   </div>
               </div>
               <div class="row g-0 feature-detail-row align-items-center cloneable element p-0">
                   <div class="col-lg-3 feature-name-col d-flex align-items-center ps-3 pe-3">...........</div>
                   <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-close-thick"></i></div>
                   <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                   <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
               </div>
               <div class="row g-0 feature-detail-row align-items-center cloneable element p-0">
                   <div class="col-lg-3 feature-name-col d-flex align-items-center ps-3 pe-3">...........</div>
                   <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                   <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                   <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
               </div>
           </div>

            <div class="cloneable py-0">
                <div class="row g-0 py-3 category-detail-row rounded-lg background-color-element cloneable element align-items-center">
                    <div class="col-lg-3 category-title-col d-flex align-items-center ps-2 pe-2">
                        <span class="category-badge d-inline-block background-color-element element fw-medium py-1 px-3 rounded-lg fs-sm">Category</span>
                    </div>
                </div>
                <div class="row g-0 feature-detail-row align-items-center cloneable element p-0">
                    <div class="col-lg-3 feature-name-col d-flex align-items-center ps-3 pe-3">...........</div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                </div>
                <div class="row g-0 feature-detail-row align-items-center cloneable element p-0">
                    <div class="col-lg-3 feature-name-col d-flex align-items-center ps-3 pe-3">...........</div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                </div>
            </div>

            <div class="cloneable py-0">
                <div class="row g-0 py-3 category-detail-row rounded-lg background-color-element cloneable element align-items-center">
                    <div class="col-lg-3 category-title-col d-flex align-items-center ps-2 pe-2">
                        <span class="category-badge d-inline-block background-color-element element fw-medium py-1 px-3 rounded-lg fs-sm">Category</span>
                    </div>
                </div>
                <div class="row g-0 feature-detail-row align-items-center cloneable element p-0">
                    <div class="col-lg-3 feature-name-col d-flex align-items-center ps-3 pe-3">...........</div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                </div>
                <div class="row g-0 feature-detail-row align-items-center cloneable element p-0">
                    <div class="col-lg-3 feature-name-col d-flex align-items-center ps-3 pe-3">...........</div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                </div>
                <div class="row g-0 feature-detail-row align-items-center cloneable element p-0">
                    <div class="col-lg-3 feature-name-col d-flex align-items-center ps-3 pe-3">...........</div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                </div>
                <div class="row g-0 feature-detail-row align-items-center cloneable element p-0">
                    <div class="col-lg-3 feature-name-col d-flex align-items-center ps-3 pe-3">...........</div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                </div>
                <div class="row g-0 feature-detail-row align-items-center cloneable element p-0">
                    <div class="col-lg-3 feature-name-col d-flex align-items-center ps-3 pe-3">...........</div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-close-thick"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                </div>
                <div class="row g-0 feature-detail-row align-items-center cloneable element p-0 is-last-row">
                    <div class="col-lg-3 feature-name-col d-flex align-items-center ps-3 pe-3">...........</div>
                    <div class="col-lg-3 plan-col background-color-element element plan-col-first check-cell-col rounded-lg-bottom-start p-3"><i class="mdi mdi-close-thick"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element check-cell-col p-3"><i class="mdi mdi-check-bold"></i></div>
                    <div class="col-lg-3 plan-col background-color-element element plan-col-last check-cell-col rounded-lg-bottom-end p-3"><i class="mdi mdi-check-bold"></i></div>
                </div>
            </div>
        </div>
    </div>

    <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>
