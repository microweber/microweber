<?php
/*
type: layout
name: Animation Bg 3 - Gradient Stats
position: 3
categories: Animated Backgrounds
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section animation-bg-skin-3 text-white"
    field-name="layout-animation-bg-skin-3"
>
    <x-row class="justify-content-center">
        <x-col size="12" class="mx-auto">
            <div class="p-5 text-center" style="background: linear-gradient(135deg,#11998e,#38ef7d); border-radius: 1rem;">
                <h2 class="fw-bold text-white" data-mwplaceholder="Enter title here">Trusted by teams worldwide</h2>
                <p class="lead text-white mx-auto mt-2 mb-5" style="max-width: 600px;" data-mwplaceholder="Enter text here">Numbers that speak for themselves. Here is what our growing community looks like today.</p>
                <div class="row row-cols-3 text-center">
                    <div class="col">
                        <div class="display-5 fw-bold text-white" data-mwplaceholder="Enter title here">12k+</div>
                        <p class="text-white mb-0" data-mwplaceholder="Enter text here">Active users</p>
                    </div>
                    <div class="col">
                        <div class="display-5 fw-bold text-white" data-mwplaceholder="Enter title here">98%</div>
                        <p class="text-white mb-0" data-mwplaceholder="Enter text here">Satisfaction rate</p>
                    </div>
                    <div class="col">
                        <div class="display-5 fw-bold text-white" data-mwplaceholder="Enter title here">24/7</div>
                        <p class="text-white mb-0" data-mwplaceholder="Enter text here">Support available</p>
                    </div>
                </div>
                <div class="mt-5">
                    <module type="btn" button_style="btn-light" button_size="btn-lg px-5" text="Get started" id="{{ $params['id'] }}-animbg3"/>
                </div>
            </div>
        </x-col>
    </x-row>
</x-layout-section>
