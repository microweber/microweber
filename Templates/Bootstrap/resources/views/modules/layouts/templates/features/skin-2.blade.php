<?php

/*

type: layout

name: Features 2 - Advantages Grid

position: 2

categories: Features

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


<section class="section features-skin-2-advantages <?php print $layout_classes; ?> edit safe-mode" field="layout-features-skin-2-{{ $params['id'] }}" rel="module">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="mw-layout-container container">
        <div class="row text-center safe-mode">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="regular-mode">
                    <h2 class="display-5 fw-bold" data-mwplaceholder="Enter title here">Why choose our hosting</h2>
                    <p class="fs-5 text-muted" data-mwplaceholder="Enter text here">Everything you need to launch and scale — with no hidden costs and no lock-in.</p>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mt-3">
            <div class="col cloneable element text-center safe-mode background-color-element">
                <div class="p-3">
                    <i class="features-skin-2-icons mb-3 safe-element no-typing mw-micon-Speed-Fast d-block fs-1 text-primary"></i>
                    <h5 class="fw-semibold" data-mwplaceholder="Enter title here">Blazing-fast NVMe</h5>
                    <p class="text-muted small regular-mode" data-mwplaceholder="Enter text here">NVMe storage and HTTP/3 deliver up to 20x faster load times than traditional hosting.</p>
                </div>
            </div>

            <div class="col cloneable element text-center safe-mode background-color-element">
                <div class="p-3">
                    <i class="features-skin-2-icons mb-3 safe-element no-typing mw-micon-Shield-Protected d-block fs-1 text-primary"></i>
                    <h5 class="fw-semibold" data-mwplaceholder="Enter title here">Enterprise security</h5>
                    <p class="text-muted small regular-mode" data-mwplaceholder="Enter text here">Free SSL, daily backups, DDoS protection and malware scanning — included on every plan.</p>
                </div>
            </div>

            <div class="col cloneable element text-center safe-mode background-color-element">
                <div class="p-3">
                    <i class="features-skin-2-icons mb-3 safe-element no-typing mw-micon-Headphones-Support d-block fs-1 text-primary"></i>
                    <h5 class="fw-semibold" data-mwplaceholder="Enter title here">Human 24/7 support</h5>
                    <p class="text-muted small regular-mode" data-mwplaceholder="Enter text here">Reach a real engineer by chat, email or phone — average response time under 3 minutes.</p>
                </div>
            </div>

            <div class="col cloneable element text-center safe-mode background-color-element">
                <div class="p-3">
                    <i class="features-skin-2-icons mb-3 safe-element no-typing mw-micon-Certified-Badge d-block fs-1 text-primary"></i>
                    <h5 class="fw-semibold" data-mwplaceholder="Enter title here">99.9% uptime SLA</h5>
                    <p class="text-muted small regular-mode" data-mwplaceholder="Enter text here">Redundant infrastructure across geographically distributed datacenters with automatic failover.</p>
                </div>
            </div>

            <div class="col cloneable element text-center safe-mode background-color-element">
                <div class="p-3">
                    <i class="features-skin-2-icons mb-3 safe-element no-typing mw-micon-Globe-Earth d-block fs-1 text-primary"></i>
                    <h5 class="fw-semibold" data-mwplaceholder="Enter title here">Global CDN</h5>
                    <p class="text-muted small regular-mode" data-mwplaceholder="Enter text here">Serve your site from 200+ edge locations worldwide so visitors always get the closest copy.</p>
                </div>
            </div>

            <div class="col cloneable element text-center safe-mode background-color-element">
                <div class="p-3">
                    <i class="features-skin-2-icons mb-3 safe-element no-typing mw-micon-Database-SQL d-block fs-1 text-primary"></i>
                    <h5 class="fw-semibold" data-mwplaceholder="Enter title here">One-click installs</h5>
                    <p class="text-muted small regular-mode" data-mwplaceholder="Enter text here">Spin up WordPress, Laravel, Drupal, Joomla and 100+ other apps in seconds.</p>
                </div>
            </div>

            <div class="col cloneable element text-center safe-mode background-color-element">
                <div class="p-3">
                    <i class="features-skin-2-icons mb-3 safe-element no-typing mw-micon-CreditCard-Payment d-block fs-1 text-primary"></i>
                    <h5 class="fw-semibold" data-mwplaceholder="Enter title here">No hidden fees</h5>
                    <p class="text-muted small regular-mode" data-mwplaceholder="Enter text here">Transparent pricing — the price you see is the price you pay. Cancel any time.</p>
                </div>
            </div>

            <div class="col cloneable element text-center safe-mode background-color-element">
                <div class="p-3">
                    <i class="features-skin-2-icons mb-3 safe-element no-typing mw-micon-ArrowUp-Growth d-block fs-1 text-primary"></i>
                    <h5 class="fw-semibold" data-mwplaceholder="Enter title here">Scale on demand</h5>
                    <p class="text-muted small regular-mode" data-mwplaceholder="Enter text here">Upgrade with a single click when your traffic spikes — no downtime, no migration.</p>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>
