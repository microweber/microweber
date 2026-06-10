<?php

/*

type: layout

name: Pricing 1

position: 1

categories: Pricing

*/

?>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-pricing-skin-1"
    :no-drop="true"
    container-class="mw-layout-container py-3"
>
    <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
        <x-section-heading tag="h1" subtitle="Quickly build an effective pricing table for your potential customers with this Bootstrap example. It's built with default Bootstrap components and utilities with little customization." class="display-4 fw-normal">Pricing</x-section-heading>
    </div>

    <x-pricing-table :columns="3" class="mb-3">
        <x-pricing-row
            plan-name="Free"
            price="$0"
            period="/mo"
            :features="['10 users included', '2 GB of storage', 'Email support', 'Help center access']"
            button-text="Sign up for free"
            button-style="btn btn-lg btn-outline-primary"
        >
            <x-slot name="actions">
                <module type="btn" id="{{ $params['id'] }}-free" button_style="w-100 btn btn-lg btn-outline-primary" button_text="Sign up for free"/>
            </x-slot>
        </x-pricing-row>

        <x-pricing-row
            plan-name="Pro"
            price="$15"
            period="/mo"
            :features="['20 users included', '10 GB of storage', 'Priority email support', 'Help center access']"
            :highlighted="true"
            button-text="Get started"
        >
            <x-slot name="actions">
                <module type="btn" id="{{ $params['id'] }}-pro" button_style="w-100 btn btn-lg btn-primary" button_text="Get started"/>
            </x-slot>
        </x-pricing-row>

        <x-pricing-row
            plan-name="Enterprise"
            price="$29"
            period="/mo"
            :features="['30 users included', '15 GB of storage', 'Phone and email support', 'Help center access']"
            button-text="Contact us"
        >
            <x-slot name="actions">
                <module type="btn" id="{{ $params['id'] }}-enterprise" button_style="w-100 btn btn-lg btn-primary" button_text="Contact us"/>
            </x-slot>
        </x-pricing-row>
    </x-pricing-table>

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
</x-layout-section>
