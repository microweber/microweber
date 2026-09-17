<?php
/*
type: layout
name: Call To Action 2 - Split
position: 2
categories: Call To Action
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section call-to-action-skin-2 bg-dark text-white py-6"
    field-name="layout-call-to-action-skin-2"
>
    <x-row class="align-items-center">
        <x-col size="12" size-lg="8">
            <h2 class="display-6 fw-bold mb-2" data-mwplaceholder="Enter title here">Take your store to the next level</h2>
            <p class="lead text-white-50 mb-0" data-mwplaceholder="Enter text here">Everything you need to sell online, manage orders and delight your customers in one place.</p>
        </x-col>
        <x-col size="12" size-lg="4" class="d-flex align-items-center justify-content-lg-end mt-4 mt-lg-0">
            <module type="btn" id="{{ $params['id'] }}-cta2-btn" button_style="btn-primary" button_size="btn-lg px-5" text="Start now"/>
        </x-col>
    </x-row>
</x-layout-section>
