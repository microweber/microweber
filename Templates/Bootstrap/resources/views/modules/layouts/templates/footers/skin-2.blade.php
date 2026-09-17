<?php
/*
type: layout
name: Footers 2 - Simple centered
position: 2
categories: Footers
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section footers-skin-2 bg-dark text-white"
    field-name="layout-footers-skin-2"
>
    <div class="text-center">
        <div class="mb-4 d-inline-block">
            <module type="logo" id="{{ $params['id'] }}-logo" class="mw-footer-logo"/>
        </div>

        <p class="text-white-50 mx-auto mb-4" style="max-width: 640px;" data-mwplaceholder="Enter text here">
            Build beautiful websites without writing a single line of code. Everything you need, in one friendly place.
        </p>

        <nav class="mb-4">
            <ul class="nav justify-content-center">
                <li class="nav-item"><a class="nav-link text-white-50" href="#" data-mwplaceholder="Enter text here">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white-50" href="#" data-mwplaceholder="Enter text here">About</a></li>
                <li class="nav-item"><a class="nav-link text-white-50" href="#" data-mwplaceholder="Enter text here">Services</a></li>
                <li class="nav-item"><a class="nav-link text-white-50" href="#" data-mwplaceholder="Enter text here">Pricing</a></li>
                <li class="nav-item"><a class="nav-link text-white-50" href="#" data-mwplaceholder="Enter text here">Contact</a></li>
            </ul>
        </nav>

        <div class="d-flex justify-content-center mb-4">
            <module type="social_links" template="skin-4" id="{{ $params['id'] }}-social-links"/>
        </div>

        <hr class="border-secondary my-4">

        <p class="small text-white-50 mb-0" data-mwplaceholder="Enter text here">
            &copy; {{ date('Y') }} Your Company. All rights reserved.
        </p>
    </div>
</x-layout-section>
