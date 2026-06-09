<?php

/*

type: layout

name: Features 1

position: 1

categories: Features

*/

?>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section features-skin-2"
    field-name="layout-features-skin-1"
>
    <x-row class="text-center safe-mode">
        <x-col size="12" size-lg="8" size-xl="8" size-xxl="8" class="mx-auto">
            <div class="regular-mode">
                <h4 data-mwplaceholder="Enter title here">The Feature Title</h4>
            </div>
        </x-col>
    </x-row>

    <x-row class="text-center mt-7">
        @include('templates.bootstrap::partials.feature-item', [
            'iconClass'  => 'mw-micon-Add-User',
            'text'       => 'To get started in learning how to observe the stars much better, there are some basic things.',
            'buttonId'   => $params['id'] . '-btn-1',
        ])

        @include('templates.bootstrap::partials.feature-item', [
            'iconClass'  => 'mw-micon-Add-UserStar',
            'text'       => 'To get started in learning how to observe the stars much better, there are some basic things.',
            'buttonId'   => $params['id'] . '-btn-2',
        ])

        @include('templates.bootstrap::partials.feature-item', [
            'iconClass'  => 'mw-micon-Business-ManWoman',
            'text'       => 'To get started in learning how to observe the stars much better, there are some basic things.',
            'buttonId'   => $params['id'] . '-btn-3',
        ])
    </x-row>
</x-layout-section>
