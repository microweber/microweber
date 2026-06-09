<?php

/*

type: layout

name: E-commerce 1

position: 1

categories: Ecommerce

*/

?>

@component('templates.bootstrap::partials.layout-section', [
    'params'         => $params,
    'classes'        => $classes,
    'layout_classes' => $layout_classes ?? '',
    'sectionClass'   => 'section',
    'editableClass'  => '',
])
    <x-row class="justify-content-between">
        <x-col size="12" size-lg="9" size-xl="9" size-xxl="9">
            <module type="shop/products" id="{{ $params['id'] }}-shop-products" template="default"/>
        </x-col>
        <x-col size="12" size-lg="3" size-xl="3" size-xxl="3">
            <div class="sidebar">
                <div class="sidebar__widget mb-4">
                    <module type="categories" id="{{ $params['id'] }}-categories" template="skin-1"/>
                </div>
            </div>
        </x-col>
    </x-row>
@endcomponent
