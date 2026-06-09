<?php

/*

type: layout

name: Jumbotron 1

position: 1

categories: Jumbotron

*/

?>

@component('templates.bootstrap::partials.layout-section', [
    'params'          => $params,
    'classes'         => $classes,
    'layout_classes'  => $layout_classes ?? '',
    'sectionClass'    => 'section mw-layout-dark-background py-0 d-flex align-items-center justify-content-center',
    'fieldName'       => 'layout-jumbotron-skin-1',
    'editableClass'   => 'edit safe-mode',
    'noDrop'          => true,
    'hasSpacers'      => false,
    'backgroundAttrs' => 'data-background-color="#00000060" data-background-image="' . asset('templates/bootstrap/img/hero.jpg') . '"',
    'containerClass'  => 'mw-layout-container py-4 mw-header-section-mh-100vh d-flex align-items-center justify-content-center',
])
    <x-row class="text-center">
        <x-col size="12" class="mx-auto text-white">
            <h1 data-mwplaceholder="Enter title here" class="header-section-title mb-7">Describe your company</h1>
            <p data-mwplaceholder="Enter text here" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>
            <module type="btn" id="{{ $params['id'] }}-btn" button_style="btn-primary" button_size="btn-lg px-5" button_text="Call to action"/>
        </x-col>
    </x-row>
@endcomponent
