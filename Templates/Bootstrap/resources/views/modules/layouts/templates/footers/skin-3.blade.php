<?php
/*
type: layout
name: Footers 3 - Multi-column links
position: 3
categories: Footers
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section footers-skin-3 bg-dark text-white"
    field-name="layout-footers-skin-3"
>
    <x-row class="row-cols-1 row-cols-md-4 g-4">
        <x-col>
            <div class="mb-3 d-inline-block">
                <module type="logo" id="{{ $params['id'] }}-logo" class="mw-footer-logo"/>
            </div>
            <p class="text-white-50 small" data-mwplaceholder="Enter text here">
                We help teams launch fast, look great and grow with confidence. Trusted by thousands of makers worldwide.
            </p>
        </x-col>

        <x-col>
            <h5 class="fw-bold mb-3" data-mwplaceholder="Enter title here">Company</h5>
            <ul class="list-unstyled">
                <li class="mb-2"><a class="link-light text-decoration-none" href="#" data-mwplaceholder="Enter text here">About us</a></li>
                <li class="mb-2"><a class="link-light text-decoration-none" href="#" data-mwplaceholder="Enter text here">Careers</a></li>
                <li class="mb-2"><a class="link-light text-decoration-none" href="#" data-mwplaceholder="Enter text here">Press</a></li>
                <li class="mb-2"><a class="link-light text-decoration-none" href="#" data-mwplaceholder="Enter text here">Blog</a></li>
            </ul>
        </x-col>

        <x-col>
            <h5 class="fw-bold mb-3" data-mwplaceholder="Enter title here">Product</h5>
            <ul class="list-unstyled">
                <li class="mb-2"><a class="link-light text-decoration-none" href="#" data-mwplaceholder="Enter text here">Features</a></li>
                <li class="mb-2"><a class="link-light text-decoration-none" href="#" data-mwplaceholder="Enter text here">Pricing</a></li>
                <li class="mb-2"><a class="link-light text-decoration-none" href="#" data-mwplaceholder="Enter text here">Integrations</a></li>
                <li class="mb-2"><a class="link-light text-decoration-none" href="#" data-mwplaceholder="Enter text here">Changelog</a></li>
            </ul>
        </x-col>

        <x-col>
            <h5 class="fw-bold mb-3" data-mwplaceholder="Enter title here">Support</h5>
            <ul class="list-unstyled">
                <li class="mb-2"><a class="link-light text-decoration-none" href="#" data-mwplaceholder="Enter text here">Help center</a></li>
                <li class="mb-2"><a class="link-light text-decoration-none" href="#" data-mwplaceholder="Enter text here">Documentation</a></li>
                <li class="mb-2"><a class="link-light text-decoration-none" href="#" data-mwplaceholder="Enter text here">Community</a></li>
                <li class="mb-2"><a class="link-light text-decoration-none" href="#" data-mwplaceholder="Enter text here">Contact us</a></li>
            </ul>
        </x-col>
    </x-row>

    <hr class="border-secondary my-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
        <p class="small text-white-50 mb-3 mb-md-0" data-mwplaceholder="Enter text here">
            &copy; {{ date('Y') }} Your Company. All rights reserved.
        </p>
        <div class="d-flex">
            <module type="social_links" template="skin-4" id="{{ $params['id'] }}-social-links"/>
        </div>
    </div>
</x-layout-section>
