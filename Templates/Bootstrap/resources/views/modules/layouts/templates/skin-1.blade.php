<?php

/*

type: layout

name: CLEAN container mw-layout-container

position: 0

*/

?>

@component('templates.bootstrap::partials.layout-section', [
    'params'              => $params,
    'classes'             => $classes,
    'layout_classes'      => $layout_classes ?? '',
    'defaultPaddingTop'   => 'p-t-100',
    'defaultPaddingBottom' => 'p-b-100',
    'sectionClass'        => 'section nodrop clean-container mw-layout-container',
    'fieldName'           => 'layout-skin-1',
    'editableClass'       => 'edit',
    'hasBackground'       => false,
    'hasSpacers'          => false,
])
    <x-row>
        <x-col size="12" size-md="12" class="allow-drop">
            <div class="mw-row">
                <div class="mw-col" style="width:100%">
                    <div class="mw-col-container mw-layout-container">
                        <div class="mw-empty-element"></div>
                    </div>
                </div>
            </div>
        </x-col>
    </x-row>
@endcomponent
