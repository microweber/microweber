<?php
/*
type: layout
name: Menu 3 - Centered Logo Navbar
position: 3
categories: Menu
*/
?>

<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section menus-skin-3"
    field-name="layout-menus-skin-3"
>
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100">

                <div class="navbar-nav-left d-none d-lg-flex flex-fill">
                    <module type="menu" name="header_menu" id="{{ $params['id'] }}-menu" template="navbar"/>
                </div>

                <div class="text-center flex-shrink-0 px-lg-4 order-lg-0">
                    <module type="logo" id="{{ $params['id'] }}-logo" class="navbar-brand d-inline-block p-0 mw-big-header-logo w-auto"/>
                </div>

                <div class="d-none d-lg-flex flex-fill justify-content-end align-items-center gap-3">
                    <module type="social_links" id="{{ $params['id'] }}-social" template="skin-1"/>
                    <module type="btn" id="{{ $params['id'] }}-btn" button_size="btn-sm" button_text="CONTACT US"/>
                </div>

                <button class="navbar-toggler border-0 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $params['id'] }}-nav" aria-controls="{{ $params['id'] }}-nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse d-lg-none" id="{{ $params['id'] }}-nav">
                <div class="py-2">
                    <module type="menu" name="header_menu" id="{{ $params['id'] }}-menu-mobile" template="navbar"/>
                </div>
                <div class="d-flex align-items-center gap-3 py-2">
                    <module type="social_links" id="{{ $params['id'] }}-social-mobile" template="skin-1"/>
                    <module type="btn" id="{{ $params['id'] }}-btn-mobile" button_size="btn-sm" button_text="CONTACT US"/>
                </div>
            </div>
        </div>
    </nav>
</x-layout-section>
