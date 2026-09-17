<?php
/*
type: layout
name: Misc 4 - Newsletter Band
position: 4
categories: Misc
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section misc-skin-4 bg-light py-6"
    field-name="layout-misc-skin-4"
>
    <x-row class="justify-content-center text-center">
        <x-col size="12" size-lg="8" class="mx-auto">
            <x-section-heading tag="h2" class="display-5 fw-bold">Stay in the loop</x-section-heading>
            <p class="lead text-muted" data-mwplaceholder="Enter text here">Join our newsletter for product updates, tips and exclusive offers — straight to your inbox, no spam ever.</p>
            <div class="row justify-content-center mt-4">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="input-group input-group-lg">
                        <input type="email" class="form-control form-control-lg" placeholder="Enter your email" aria-label="Email address">
                        <module type="btn" id="{{ $params['id'] }}-misc-btn4" button_style="btn-primary" button_size="btn-lg px-4" text="Subscribe"/>
                    </div>
                </div>
            </div>
        </x-col>
    </x-row>
</x-layout-section>
