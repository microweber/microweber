<?php

/*

type: layout

name: Text block 1

position: 1

categories: Text block

*/

?>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-text-block-skin-1"
    :no-drop="true"
>
    <x-row class="text-center">
        <x-col size="12" size-lg="8" size-xl="8" size-xxl="8" class="mx-auto">
            <x-section-heading tag="h5">Pictures In The Sky</x-section-heading>
            <p data-mwplaceholder="Enter text here">The $79 iWork '08 appears to be a good deal for anyone needing an affordable office suite for the Mac. Apple has finally added a spreadsheet application. At first glance, Numbers is an elegant no-brainer for anyone migrating from Microsoft Excel.</p>
            <br><br><br>
        </x-col>
    </x-row>

    <div></div>

    <module type="testimonials" id="{{ $params['id'] }}-testimonials" template="skin-10" project_name="Testimonials 1"/>
</x-layout-section>
