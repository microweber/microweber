<?php
/*
type: layout
name: Videos 2 - Split with details
position: 2
categories: Videos
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section videos-skin-2"
    field-name="layout-videos-skin-2"
>
    <x-row class="align-items-center g-5">
        <x-col size="12" size-lg="6">
            <h2 class="fw-bold mb-3" data-mwplaceholder="Add a heading">
                See how it works
            </h2>
            <p class="lead text-muted mb-4" data-mwplaceholder="Add a short description">
                A quick walkthrough of the features that matter most, so you can get up and running in minutes.
            </p>
            <ul class="list-unstyled mb-4">
                <li class="d-flex mb-3">
                    <i class="mw-micon-Check text-primary fs-5 me-3"></i>
                    <span data-mwplaceholder="First benefit">Set up in just a few clicks</span>
                </li>
                <li class="d-flex mb-3">
                    <i class="mw-micon-Check text-primary fs-5 me-3"></i>
                    <span data-mwplaceholder="Second benefit">Works on every device, out of the box</span>
                </li>
                <li class="d-flex mb-3">
                    <i class="mw-micon-Check text-primary fs-5 me-3"></i>
                    <span data-mwplaceholder="Third benefit">Friendly support whenever you need it</span>
                </li>
            </ul>
            <module type="btn" id="{{ $params['id'] }}-btn1" button_style="btn-primary" button_size="btn-lg" button_text="Get started"/>
        </x-col>

        <x-col size="12" size-lg="6">
            <module type="video" id="{{ $params['id'] }}-video2"/>
        </x-col>
    </x-row>
</x-layout-section>
