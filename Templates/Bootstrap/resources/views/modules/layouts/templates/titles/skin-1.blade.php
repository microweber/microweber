<?php

/*

type: layout

name: Titles 1

position: 1

categories: Titles

*/

?>

@component('templates.bootstrap::partials.layout-section', [
    'params'         => $params,
    'classes'        => $classes,
    'layout_classes' => $layout_classes ?? '',
    'sectionClass'   => 'section',
    'fieldName'      => 'layout-titles-skin-1',
    'noDrop'         => true,
])
    <x-row class="text-center mb-5 nodrop">
        <x-col size-lg="10" size-xl="10" size-xxl="10" class="mx-auto allow-drop">
            <h1 class="mb-3"><?php print content_title(); ?></h1>
            <p class="lead edit" field="layout-titles-skin-1-description-{{ $params['id'] }}" rel="module">Discover our latest updates, articles and offerings.</p>
        </x-col>
    </x-row>
@endcomponent
