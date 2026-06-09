<?php

/*

type: layout

name: CLEAN container mw-layout-container

position: 0

*/

?>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    default-padding-top="p-t-100"
    default-padding-bottom="p-b-100"
    section-class="section nodrop clean-container mw-layout-container"
    field-name="layout-skin-1"
    editable-class="edit"
    :has-background="false"
    :has-spacers="false"
>
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
</x-layout-section>
