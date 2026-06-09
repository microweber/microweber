<?php

/*

type: layout

name: Text block 1

position: 1

categories: Text block

*/

?>

@component('templates.bootstrap::partials.layout-section', [
    'params'         => $params,
    'classes'        => $classes,
    'layout_classes' => $layout_classes ?? '',
    'sectionClass'   => 'section',
    'fieldName'      => 'layout-text-block-skin-1',
    'noDrop'         => true,
])
    <x-row class="text-center">
        <x-col size="12" size-lg="8" size-xl="8" size-xxl="8" class="mx-auto">
            <h5 data-mwplaceholder="Enter title here">Pictures In The Sky</h5>
            <p data-mwplaceholder="Enter text here">The $79 iWork '08 appears to be a good deal for anyone needing an affordable office suite for the Mac. Apple has finally added a spreadsheet application. At first glance, Numbers is an elegant no-brainer for anyone migrating from Microsoft Excel.</p>
            <br><br><br>
        </x-col>
    </x-row>

    <div></div>

    <module type="testimonials" id="{{ $params['id'] }}-testimonials" template="skin-10" project_name="Testimonials 1"/>
@endcomponent
