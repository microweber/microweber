<?php

/*

type: layout

name: Footers 1

position: 1

categories: Footers

*/

?>

@php
    // Inline padding computation — @include('partials.layout-classes') cannot set
    // $layout_classes here because @include runs in its own scope (the assignment
    // does not propagate back). See partials/layout-section.blade.php for context.
    $classes = $classes ?? [];
    $layout_classes = trim(($layout_classes ?? '') . ' ' . ($classes['padding_top'] ?? '') . ' ' . ($classes['padding_bottom'] ?? ''));
@endphp

<x-section class="footer-background {{ $layout_classes }} edit safe-mode"
    field="layout-footer-skin-1-{{ $params['id'] }}" rel="module">
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <x-container class="mw-layout-container">
        <x-row class="text-md-start text-center">

            <x-col size="12" size-lg="5" size-xl="5" size-xxl="5" class="mb-4 mb-lg-0">
                <div class="edit" field="layout-footer-skin-1-company-{{ $params['id'] }}" rel="module">
                    <p class="font-weight-bold">Website Builder and CMS</p>
                    <br>
                    <small>This is a website builder and content management system of new generation.</small>
                    <br>
                </div>
                <div class="footer-19-menu d-flex justify-content-lg-start justify-content-center ps-0 mt-3">
                    <module type="menu" template="simple" name="footer_menu" id="{{ $params['id'] }}-menu"/>
                </div>
            </x-col>

            <x-col size="12" size-lg="4" size-xl="4" size-xxl="4" class="mb-4 mb-lg-0">
                <div class="edit" field="layout-footer-skin-1-phone-{{ $params['id'] }}" rel="module">
                    <small>Phone</small>
                    <p class="mt-2">123-456-7890</p>
                </div>
                <div class="edit" field="layout-footer-skin-1-email-{{ $params['id'] }}" rel="module">
                    <small>Email</small>
                    {{-- AI-142 / A11Y-07 (cycle-126 2026-05-09): the
                         default `href=""` resolved to the current
                         page (link-to-self), confusing visitors who
                         expected a mail-to. Switched to a real
                         `mailto:` link with the same address as the
                         visible text. The address itself is a
                         placeholder; the live install replaces it
                         via the editable region. --}}
                    <p class="mt-2"><a href="mailto:mail@yourcompany.com">mail@yourcompany.com</a></p>
                </div>
                <div class="edit" field="layout-footer-skin-1-social-{{ $params['id'] }}" rel="module">
                    <p>Social</p>
                    <module type="social_links" template="skin-4" id="{{ $params['id'] }}-social-links"/>
                </div>
            </x-col>

            <x-col size="12" size-lg="3" size-xl="3" size-xxl="3" class="edit" field="layout-footer-skin-1-addresses-{{ $params['id'] }}" rel="module">
                <small>California</small>
                <p class="mt-2">21 Lebsack Harbor Apt. 276 Palo Alto, CA</p>

                <small>New York</small>
                <p class="mt-2">74 Howell Islands Suite 834 Rochester, NY</p>
            </x-col>

        </x-row>
    </x-container>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</x-section>

<x-section class="py-2" style="background-color: #f5f5f5;">
    <x-container class="mw-layout-container py-2">
        <div class="col-12 d-md-flex text-center">
            <small class="col-sm-6 text-md-start text-center edit" field="footer-reserved-skin-1-{{ $params['id'] }}" rel="module">&copy; All Rights Reserved.</small>
            <small class="col-sm-6 mb-0 noedit text-md-end text-center"><?php print powered_by_link(); ?></small>
        </div>
    </x-container>
</x-section>
