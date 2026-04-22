<?php

/*

type: layout

name: Pricing 3 - Compare Matrix

position: 3

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


<section class="section pricing-skin-3 <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-pricing-skin-3-{{ $params['id'] }}" rel="module">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="container mw-layout-container py-3">
        <div class="text-center mx-auto pb-4" style="max-width: 720px;">
            <h2 class="display-5 fw-bold">Compare all features</h2>
            <p class="fs-5 text-muted">Every plan comes with the resources you need to launch, grow and scale. Pick the one that fits your stage.</p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center mb-0 pricing-compare-table">
                <thead class="sticky-top bg-body">
                <tr>
                    <th style="width: 28%;" class="text-start">&nbsp;</th>
                    <th style="width: 18%;">
                        <div class="fw-semibold">Start</div>
                        <div class="text-muted small">$2.99/mo</div>
                    </th>
                    <th style="width: 18%;" class="bg-primary bg-opacity-10 rounded-top">
                        <div class="fw-semibold text-primary">Plus</div>
                        <div class="text-muted small">$5.99/mo</div>
                        <span class="badge bg-primary text-uppercase mt-1">Popular</span>
                    </th>
                    <th style="width: 18%;">
                        <div class="fw-semibold">Turbo</div>
                        <div class="text-muted small">$9.99/mo</div>
                    </th>
                    <th style="width: 18%;">
                        <div class="fw-semibold">Business</div>
                        <div class="text-muted small">$19.99/mo</div>
                    </th>
                </tr>
                </thead>

                <tbody>
                <tr class="table-light">
                    <th colspan="5" class="text-start fw-semibold text-uppercase small">Resources</th>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">Websites</th>
                    <td>1</td>
                    <td class="bg-primary bg-opacity-10">Unlimited</td>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">Storage</th>
                    <td>10 GB SSD</td>
                    <td class="bg-primary bg-opacity-10">50 GB SSD</td>
                    <td>150 GB NVMe</td>
                    <td>500 GB NVMe</td>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">Monthly traffic</th>
                    <td>Unmetered</td>
                    <td class="bg-primary bg-opacity-10">Unmetered</td>
                    <td>Unmetered</td>
                    <td>Unmetered</td>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">CPU &amp; RAM</th>
                    <td>1x</td>
                    <td class="bg-primary bg-opacity-10">2x</td>
                    <td>4x</td>
                    <td>8x</td>
                </tr>

                <tr class="table-light">
                    <th colspan="5" class="text-start fw-semibold text-uppercase small">Domains &amp; email</th>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">Free domain</th>
                    <td><i class="mdi mdi-check text-success"></i></td>
                    <td class="bg-primary bg-opacity-10"><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">Email accounts</th>
                    <td>10</td>
                    <td class="bg-primary bg-opacity-10">Unlimited</td>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">Databases</th>
                    <td>5</td>
                    <td class="bg-primary bg-opacity-10">Unlimited</td>
                    <td>Unlimited</td>
                    <td>Unlimited</td>
                </tr>

                <tr class="table-light">
                    <th colspan="5" class="text-start fw-semibold text-uppercase small">Security &amp; backups</th>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">Free SSL certificate</th>
                    <td><i class="mdi mdi-check text-success"></i></td>
                    <td class="bg-primary bg-opacity-10"><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">Daily backups</th>
                    <td><i class="mdi mdi-check text-success"></i></td>
                    <td class="bg-primary bg-opacity-10"><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">DDoS protection</th>
                    <td>Basic</td>
                    <td class="bg-primary bg-opacity-10">Standard</td>
                    <td>Standard</td>
                    <td>Advanced</td>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">Dedicated IP</th>
                    <td><i class="mdi mdi-close text-muted"></i></td>
                    <td class="bg-primary bg-opacity-10"><i class="mdi mdi-close text-muted"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                </tr>

                <tr class="table-light">
                    <th colspan="5" class="text-start fw-semibold text-uppercase small">Developer tools</th>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">SSH / SFTP access</th>
                    <td><i class="mdi mdi-check text-success"></i></td>
                    <td class="bg-primary bg-opacity-10"><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">Git deploy</th>
                    <td><i class="mdi mdi-close text-muted"></i></td>
                    <td class="bg-primary bg-opacity-10"><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">Staging environment</th>
                    <td><i class="mdi mdi-close text-muted"></i></td>
                    <td class="bg-primary bg-opacity-10"><i class="mdi mdi-close text-muted"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                </tr>

                <tr class="table-light">
                    <th colspan="5" class="text-start fw-semibold text-uppercase small">Support</th>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">24/7 chat &amp; email</th>
                    <td><i class="mdi mdi-check text-success"></i></td>
                    <td class="bg-primary bg-opacity-10"><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">Priority queue</th>
                    <td><i class="mdi mdi-close text-muted"></i></td>
                    <td class="bg-primary bg-opacity-10"><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                </tr>
                <tr>
                    <th scope="row" class="text-start fw-normal">Dedicated account manager</th>
                    <td><i class="mdi mdi-close text-muted"></i></td>
                    <td class="bg-primary bg-opacity-10"><i class="mdi mdi-close text-muted"></i></td>
                    <td><i class="mdi mdi-close text-muted"></i></td>
                    <td><i class="mdi mdi-check text-success"></i></td>
                </tr>
                </tbody>

                <tfoot>
                <tr>
                    <th class="text-start">&nbsp;</th>
                    <td><module type="btn" id="{{ $params['id'] }}-cta-1" button_style="btn btn-sm btn-outline-primary" button_text="Choose Start"/></td>
                    <td class="bg-primary bg-opacity-10"><module type="btn" id="{{ $params['id'] }}-cta-2" button_style="btn btn-sm btn-primary" button_text="Choose Plus"/></td>
                    <td><module type="btn" id="{{ $params['id'] }}-cta-3" button_style="btn btn-sm btn-outline-primary" button_text="Choose Turbo"/></td>
                    <td><module type="btn" id="{{ $params['id'] }}-cta-4" button_style="btn btn-sm btn-outline-primary" button_text="Choose Business"/></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>
