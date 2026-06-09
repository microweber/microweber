<?php

/*

type: layout

name: Jumbotron 1

position: 1

categories: Jumbotron

*/

?>

@php
    // Build the background-module attribute string with REAL double quotes.
    // Passing &quot; entities through :background-attrs leaves literal &quot; in
    // the <module type="background"> tag, which the Microweber module parser
    // mis-reads — it swallows the section body and leaks the attrs onto the next
    // module. A bound PHP string with real quotes renders correctly via {!! !!}.
    $jumbotronBackgroundAttrs = 'data-background-color="#00000060" data-background-image="' . asset('templates/bootstrap/img/hero.jpg') . '"';
@endphp

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-dark-background py-0 d-flex align-items-center justify-content-center"
    field-name="layout-jumbotron-skin-1"
    editable-class="edit safe-mode"
    :no-drop="true"
    :has-spacers="false"
    :background-attrs="$jumbotronBackgroundAttrs"
    container-class="mw-layout-container py-4 mw-header-section-mh-100vh d-flex align-items-center justify-content-center"
>
    <x-row class="text-center">
        <x-col size="12" class="mx-auto text-white">
            <h1 data-mwplaceholder="Enter title here" class="header-section-title mb-7">Describe your company</h1>
            <p data-mwplaceholder="Enter text here" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>
            <module type="btn" id="{{ $params['id'] }}-btn" button_style="btn-primary" button_size="btn-lg px-5" button_text="Call to action"/>
        </x-col>
    </x-row>
</x-layout-section>
