<?php

/*

type: layout

name: Pricing 2 - Hosting Plans

position: 2

categories: Pricing

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = '';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? ''; $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>


<section class="section pricing-skin-2 <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-pricing-skin-2-{{ $params['id'] }}" rel="module">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="container mw-layout-container py-3">
        <div class="pricing-header pb-4 mx-auto text-center" style="max-width: 720px;">
            <h2 class="display-5 fw-bold">Choose your hosting plan</h2>
            <p class="fs-5 text-muted">Fast, secure and scalable — every plan includes a free domain, SSL and 24/7 support. Upgrade or downgrade any time.</p>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 text-center">
            <div class="col">
                <div class="card h-100 rounded-3 shadow-sm">
                    <div class="card-header py-3 bg-transparent">
                        <h4 class="my-0 fw-normal">Start</h4>
                        <small class="text-muted">For a first personal site</small>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h1 class="card-title pricing-card-title mb-0">$2.99<small class="text-muted fw-light fs-6">/mo</small></h1>
                        <small class="text-muted d-block mb-3">billed annually</small>
                        <ul class="list-unstyled mt-2 mb-4 text-start">
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>1 website</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>10 GB SSD storage</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>Unmetered traffic</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>Free SSL certificate</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>Daily backups</li>
                        </ul>
                        <div class="mt-auto">
                            <module type="btn" id="{{ $params['id'] }}-btn-1" button_style="w-100 btn btn-outline-primary" button_text="Choose Start"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 rounded-3 shadow-sm border-primary position-relative">
                    <span class="badge bg-primary position-absolute top-0 start-50 translate-middle mt-0 px-3 py-2 rounded-pill text-uppercase">Most popular</span>
                    <div class="card-header py-3 bg-primary text-white border-primary">
                        <h4 class="my-0 fw-semibold">Plus</h4>
                        <small class="opacity-75">For growing projects</small>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h1 class="card-title pricing-card-title mb-0">$5.99<small class="text-muted fw-light fs-6">/mo</small></h1>
                        <small class="text-muted d-block mb-3">billed annually</small>
                        <ul class="list-unstyled mt-2 mb-4 text-start">
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>Unlimited websites</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>50 GB SSD storage</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>Unmetered traffic</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>Free SSL + CDN</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>Priority 24/7 support</li>
                        </ul>
                        <div class="mt-auto">
                            <module type="btn" id="{{ $params['id'] }}-btn-2" button_style="w-100 btn btn-primary" button_text="Choose Plus"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 rounded-3 shadow-sm">
                    <div class="card-header py-3 bg-transparent">
                        <h4 class="my-0 fw-normal">Turbo</h4>
                        <small class="text-muted">Performance-tuned</small>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h1 class="card-title pricing-card-title mb-0">$9.99<small class="text-muted fw-light fs-6">/mo</small></h1>
                        <small class="text-muted d-block mb-3">billed annually</small>
                        <ul class="list-unstyled mt-2 mb-4 text-start">
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>Unlimited websites</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>150 GB NVMe storage</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>4x CPU &amp; RAM</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>Dedicated IP</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>Staging environment</li>
                        </ul>
                        <div class="mt-auto">
                            <module type="btn" id="{{ $params['id'] }}-btn-3" button_style="w-100 btn btn-outline-primary" button_text="Choose Turbo"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 rounded-3 shadow-sm">
                    <div class="card-header py-3 bg-transparent">
                        <h4 class="my-0 fw-normal">Business</h4>
                        <small class="text-muted">Teams and agencies</small>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h1 class="card-title pricing-card-title mb-0">$19.99<small class="text-muted fw-light fs-6">/mo</small></h1>
                        <small class="text-muted d-block mb-3">billed annually</small>
                        <ul class="list-unstyled mt-2 mb-4 text-start">
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>Unlimited websites</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>500 GB NVMe storage</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>8x CPU &amp; RAM</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>Advanced DDoS protection</li>
                            <li class="mb-2"><i class="mdi mdi-check text-primary me-2"></i>Dedicated account manager</li>
                        </ul>
                        <div class="mt-auto">
                            <module type="btn" id="{{ $params['id'] }}-btn-4" button_style="w-100 btn btn-outline-primary" button_text="Choose Business"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center text-muted mt-4 mb-0"><small>30-day money-back guarantee &middot; Cancel any time &middot; No setup fees</small></p>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>
