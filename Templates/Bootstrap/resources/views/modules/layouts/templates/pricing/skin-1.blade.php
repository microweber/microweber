<?php

/*

type: layout

name: Pricing 1

position: 1

categories: Pricing

*/

?>

@component('templates.bootstrap::partials.layout-section', [
    'params'         => $params,
    'classes'        => $classes,
    'layout_classes' => $layout_classes ?? '',
    'sectionClass'   => 'section',
    'fieldName'      => 'layout-pricing-skin-1',
    'noDrop'         => true,
    'containerClass' => 'mw-layout-container py-3',
])
    <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
        <h1 class="display-4 fw-normal">Pricing</h1>
        <p class="fs-5 text-muted">Quickly build an effective pricing table for your potential customers with this Bootstrap example. It's built with default Bootstrap components and utilities with little customization.</p>
    </div>

    <x-row class="row-cols-1 row-cols-md-3 mb-3 text-center">
        @include('templates.bootstrap::partials.pricing-card', [
            'planName'   => 'Free',
            'price'      => '$0',
            'period'     => '/mo',
            'features'   => ['10 users included', '2 GB of storage', 'Email support', 'Help center access'],
            'btnId'      => $params['id'] . '-free',
            'btnStyle'   => 'w-100 btn btn-lg btn-outline-primary',
            'btnText'    => 'Sign up for free',
        ])

        @include('templates.bootstrap::partials.pricing-card', [
            'planName'   => 'Pro',
            'price'      => '$15',
            'period'     => '/mo',
            'features'   => ['20 users included', '10 GB of storage', 'Priority email support', 'Help center access'],
            'btnId'      => $params['id'] . '-pro',
            'btnStyle'   => 'w-100 btn btn-lg btn-primary',
            'btnText'    => 'Get started',
        ])

        @include('templates.bootstrap::partials.pricing-card', [
            'planName'   => 'Enterprise',
            'price'      => '$29',
            'period'     => '/mo',
            'features'   => ['30 users included', '15 GB of storage', 'Phone and email support', 'Help center access'],
            'btnId'      => $params['id'] . '-enterprise',
            'btnStyle'   => 'w-100 btn btn-lg btn-primary',
            'btnText'    => 'Contact us',
            'highlighted'=> true,
        ])
    </x-row>

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
@endcomponent
