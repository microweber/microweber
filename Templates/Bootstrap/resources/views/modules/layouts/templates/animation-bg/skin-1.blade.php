<?php
/*
type: layout
name: Animation Bg 1 - Centered Gradient Hero
position: 1
categories: Animation Bg
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section animation-bg-skin-1 text-white"
    field-name="layout-animation-bg-skin-1"
>
    <x-row class="justify-content-center">
        <x-col size="12" class="mx-auto">
            <div class="p-5 text-center" style="background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); border-radius: 1rem;">
                <h1 class="display-4 fw-bold text-white" data-mwplaceholder="Enter title here">Build something people love</h1>
                <p class="lead text-white mx-auto mt-3 mb-4" style="max-width: 640px;" data-mwplaceholder="Enter text here">Launch faster with a platform designed to grow with your team. Everything you need, right out of the box.</p>
                <module type="btn" button_style="btn-light" button_size="btn-lg px-5" text="Get started" id="{{ $params['id'] }}-animbg1"/>
            </div>
        </x-col>
    </x-row>
</x-layout-section>
