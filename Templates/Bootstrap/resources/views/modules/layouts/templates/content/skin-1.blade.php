<?php

/*

type: layout

name: Content 1

position: 1

categories: Content

*/

?>

@component('templates.bootstrap::partials.layout-section', [
    'params'         => $params,
    'classes'        => $classes,
    'layout_classes' => $layout_classes ?? '',
    'sectionClass'   => 'section',
    'fieldName'      => 'layout-content-skin-1',
    'containerClass' => 'mw-layout-container text-center',
])
    <x-row>
        <x-col size="12" size-lg="8" size-xl="8" size-xxl="8" class="mx-auto">
            <div class="mb-4 no-element">
                <i class="safe-element no-typing mw-micon-Anchor mb-4 icon-size-64px"></i>
            </div>
            <div class="regular-mode">
                <h3 data-mwplaceholder="Enter title here">Your Story Should Evolve Over Time</h3>
                <p data-mwplaceholder="Enter text here" class="mb-3">Update your audience on new developments and how
                    <br>
                    you're overcoming challenges.
                </p>
            </div>
        </x-col>
    </x-row>
@endcomponent
