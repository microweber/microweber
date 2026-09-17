<?php
/*
type: layout
name: Call To Action 3 - Newsletter
position: 3
categories: Call To Action
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section call-to-action-skin-3 py-6"
    field-name="layout-call-to-action-skin-3"
>
    <x-row class="justify-content-center">
        <x-col size="12" size-lg="6" class="mx-auto text-center">
            <x-section-heading tag="h2" subtitle="Get product updates, tips and exclusive offers straight to your inbox." class="display-6 fw-bold">Stay in the loop</x-section-heading>
            <div class="input-group input-group-lg mt-4">
                <input type="email" class="form-control form-control-lg" placeholder="Enter your email address" aria-label="Email address">
                <module type="btn" id="{{ $params['id'] }}-cta3-btn" button_style="btn-primary" button_size="btn-lg px-4" text="Subscribe"/>
            </div>
            <p class="small text-muted mt-3 mb-0" data-mwplaceholder="Enter text here">We respect your privacy. Unsubscribe at any time.</p>
        </x-col>
    </x-row>
</x-layout-section>
