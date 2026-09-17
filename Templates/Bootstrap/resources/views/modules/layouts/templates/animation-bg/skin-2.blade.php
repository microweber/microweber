<?php
/*
type: layout
name: Animation Bg 2 - Gradient Band
position: 2
categories: Animation Bg
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section animation-bg-skin-2 text-white"
    field-name="layout-animation-bg-skin-2"
>
    <x-row class="justify-content-center">
        <x-col size="12" class="mx-auto">
            <div class="p-5 d-md-flex justify-content-between align-items-center" style="background: linear-gradient(120deg,#0d6efd,#6610f2); border-radius: 1rem;">
                <div class="mb-4 mb-md-0 me-md-4">
                    <h2 class="fw-bold text-white mb-2" data-mwplaceholder="Enter title here">Ready to take the next step?</h2>
                    <p class="lead text-white mb-0" data-mwplaceholder="Enter text here">Join thousands of teams already shipping better products, faster.</p>
                </div>
                <div class="flex-shrink-0">
                    <module type="btn" button_style="btn-light" button_size="btn-lg px-5" text="Get started" id="{{ $params['id'] }}-animbg2"/>
                </div>
            </div>
        </x-col>
    </x-row>
</x-layout-section>
