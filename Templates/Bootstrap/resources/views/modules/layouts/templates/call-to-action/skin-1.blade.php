<?php
/*
type: layout
name: Call To Action 1 - Centered
position: 1
categories: Call To Action
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section call-to-action-skin-1 bg-primary text-white py-6"
    field-name="layout-call-to-action-skin-1"
>
    <x-row class="justify-content-center">
        <x-col size="12" size-lg="8" class="mx-auto text-center">
            <x-section-heading tag="h2" subtitle="Join thousands of teams already building faster with our platform." class="display-5 fw-bold">Ready to grow your business?</x-section-heading>
            <div class="mt-4">
                <module type="btn" id="{{ $params['id'] }}-cta1-btn" button_style="btn-light" button_size="btn-lg px-5" text="Get started"/>
            </div>
        </x-col>
    </x-row>
</x-layout-section>
