<?php
/*
type: layout
name: Footers 4 - Newsletter signup
position: 4
categories: Footers
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section footers-skin-4 bg-dark text-white"
    field-name="layout-footers-skin-4"
>
    <x-row class="align-items-center g-5">
        <x-col size="12" size-lg="6">
            <div class="mb-3 d-inline-block">
                <module type="logo" id="{{ $params['id'] }}-logo" class="mw-footer-logo"/>
            </div>
            <p class="text-white-50 mb-4" style="max-width: 480px;" data-mwplaceholder="Enter text here">
                Join our community and get the tools you need to build something great. No spam, just the good stuff.
            </p>
            <div class="d-flex">
                <module type="social_links" template="skin-4" id="{{ $params['id'] }}-social-links"/>
            </div>
        </x-col>

        <x-col size="12" size-lg="6">
            <h4 class="fw-bold mb-2" data-mwplaceholder="Enter title here">Subscribe to our newsletter</h4>
            <p class="text-white-50 mb-3" data-mwplaceholder="Enter text here">
                Get the latest updates, tips and offers delivered straight to your inbox.
            </p>
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-white border-0"><i class="mw-micon-Mail"></i></span>
                <input type="email" class="form-control border-0" placeholder="Enter your email" aria-label="Email address">
                <button class="btn btn-primary" type="button">Subscribe</button>
            </div>
            <p class="small text-white-50 mt-2 mb-0" data-mwplaceholder="Enter text here">
                We care about your data. Read our privacy policy.
            </p>
        </x-col>
    </x-row>

    <hr class="border-secondary my-4">

    <p class="small text-white-50 text-center mb-0" data-mwplaceholder="Enter text here">
        &copy; {{ date('Y') }} Your Company. All rights reserved.
    </p>
</x-layout-section>
