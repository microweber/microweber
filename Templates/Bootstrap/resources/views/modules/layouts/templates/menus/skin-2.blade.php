<?php
/*
type: layout
name: Menu 2 - Logo Left Navbar
position: 2
categories: Menu
*/
?>

<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section menus-skin-2"
    field-name="layout-menus-skin-2"
>
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <module type="logo" id="{{ $params['id'] }}-logo" class="navbar-brand p-0 me-lg-4 mw-big-header-logo w-auto"/>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $params['id'] }}-nav" aria-controls="{{ $params['id'] }}-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="{{ $params['id'] }}-nav">
                <div class="mx-auto">
                    <module type="menu" name="header_menu" id="{{ $params['id'] }}-menu" template="navbar"/>
                </div>

                <div class="d-flex align-items-center gap-2 ms-lg-3 mt-3 mt-lg-0">
                    <module type="btn" id="{{ $params['id'] }}-btn" button_size="btn-sm" button_text="GET STARTED"/>
                </div>
            </div>
        </div>
    </nav>
</x-layout-section>
