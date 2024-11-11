<?php

/*

type: layout

name: Pricing 1

position: 1

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


<section class="section <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-pricing-skin-1-<?php print $params['id'] ?>" rel="module">
    <div class="container py-3">
        <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
            <h1 class="display-4 fw-normal">Pricing</h1>
            <p class="fs-5 text-muted">Quickly build an effective pricing table for your potential customers with this Bootstrap example. It’s built with default Bootstrap components and utilities with little customization.</p>
        </div>
        <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
            <div class="col">
                <div class="card mb-4 rounded-3 shadow-sm">
                    <div class="card-header py-3">
                        <h4 class="my-0 fw-normal">Free</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$0<small class="text-muted fw-light">/mo</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>10 users included</li>
                            <li>2 GB of storage</li>
                            <li>Email support</li>
                            <li>Help center access</li>
                        </ul>
                        <module type="btn" button_style="w-100 btn btn-lg btn-outline-primary" button_text="Sign up for free"/>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card mb-4 rounded-3 shadow-sm">
                    <div class="card-header py-3">
                        <h4 class="my-0 fw-normal">Pro</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$15<small class="text-muted fw-light">/mo</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>20 users included</li>
                            <li>10 GB of storage</li>
                            <li>Priority email support</li>
                            <li>Help center access</li>
                        </ul>

                        <module type="btn" button_style="w-100 btn btn-lg btn-primary" button_text="Get started"/>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card mb-4 rounded-3 shadow-sm border-primary">
                    <div class="card-header py-3 text-white bg-primary border-primary">
                        <h4 class="my-0 fw-normal">Enterprise</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">$29<small class="text-muted fw-light">/mo</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>30 users included</li>
                            <li>15 GB of storage</li>
                            <li>Phone and email support</li>
                            <li>Help center access</li>
                        </ul>
                        <module type="btn" button_style="w-100 btn btn-lg btn-primary" button_text="Contact us"/>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="display-6 text-center mb-4">Compare plans</h2>

        <div class="table-responsive">
            <table class="table text-center">
                <thead>
                <tr>
                    <th style="width: 34%;"></th>
                    <th style="width: 22%;">Free</th>
                    <th style="width: 22%;">Pro</th>
                    <th style="width: 22%;">Enterprise</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row" class="text-start">Public</th>
                    <td><i class="mdi mdi-check" style="font-size: 25px;"></i></td>
                    <td><i class="mdi mdi-check" style="font-size: 25px;"></i></td>
                    <td><i class="mdi mdi-check" style="font-size: 25px;"></i></td>
                </tr>
                <tr>
                    <th scope="row" class="text-start">Private</th>
                    <td></td>
                    <td><i class="mdi mdi-check" style="font-size: 25px;"></i></td>
                    <td><i class="mdi mdi-check" style="font-size: 25px;"></i></td>
                </tr>
                </tbody>

                <tbody>
                <tr>
                    <th scope="row" class="text-start">Permissions</th>
                    <td><i class="mdi mdi-check" style="font-size: 25px;"></i></td>
                    <td><i class="mdi mdi-check" style="font-size: 25px;"></i></td>
                    <td><i class="mdi mdi-check" style="font-size: 25px;"></i></td>
                </tr>
                <tr>
                    <th scope="row" class="text-start">Sharing</th>
                    <td></td>
                    <td><i class="mdi mdi-check" style="font-size: 25px;"></i></td>
                    <td><i class="mdi mdi-check" style="font-size: 25px;"></i></td>
                </tr>
                <tr>
                    <th scope="row" class="text-start">Unlimited members</th>
                    <td></td>
                    <td><i class="mdi mdi-check" style="font-size: 25px;"></i></td>
                    <td><i class="mdi mdi-check" style="font-size: 25px;"></i></td>
                </tr>
                <tr>
                    <th scope="row" class="text-start">Extra security</th>
                    <td></td>
                    <td></td>
                    <td><svg class="bi" width="24" height="24"><use xlink:href="#check"/></svg></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
