<?php
/*
type: layout
name: Contacts 3 - Get in touch centered
position: 3
categories: Contact Us
*/
?>
<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section contacts-skin-3"
    field-name="layout-contacts-skin-3"
>
    <x-row class="text-center">
        <x-col size="12" size-lg="8" class="mx-auto">
            <x-section-heading tag="h2">Get in touch</x-section-heading>
            <p class="lead text-muted mb-5" data-mwplaceholder="Enter text here">
                Questions, feedback or a big idea? We are always happy to talk. Pick the channel that works best for you and say hello.
            </p>

            <div class="row g-4 justify-content-center mb-5">
                <div class="col-6 col-md-4">
                    <i class="mw-micon-Map d-inline-block mb-2" style="font-size:2rem;"></i>
                    <h6 class="mb-1" data-mwplaceholder="Enter title here">Visit us</h6>
                    <p class="text-muted mb-0" data-mwplaceholder="Enter text here">123 Market Street, San Francisco</p>
                </div>
                <div class="col-6 col-md-4">
                    <i class="mw-micon-Phone d-inline-block mb-2" style="font-size:2rem;"></i>
                    <h6 class="mb-1" data-mwplaceholder="Enter title here">Call us</h6>
                    <p class="text-muted mb-0" data-mwplaceholder="Enter text here">+1 (555) 012-3456</p>
                </div>
                <div class="col-6 col-md-4">
                    <i class="mw-micon-Mail d-inline-block mb-2" style="font-size:2rem;"></i>
                    <h6 class="mb-1" data-mwplaceholder="Enter title here">Email us</h6>
                    <p class="text-muted mb-0" data-mwplaceholder="Enter text here">hello@example.com</p>
                </div>
            </div>

            <module type="btn" id="{{ $params['id'] }}-btn-contact" button_style="btn-primary" button_size="btn-lg px-5" text="Contact us"/>
        </x-col>
    </x-row>
</x-layout-section>
