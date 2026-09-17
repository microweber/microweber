<?php
/*
type: layout
name: Call To Action 4 - Boxed Card
position: 4
categories: Call To Action
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section call-to-action-skin-4 py-6"
    field-name="layout-call-to-action-skin-4"
>
    <x-row class="justify-content-center">
        <x-col size="12" size-lg="8" class="mx-auto">
            <div class="card shadow border-0 text-center">
                <div class="card-body p-5">
                    <h2 class="display-6 fw-bold mb-3" data-mwplaceholder="Enter title here">Let's build something great together</h2>
                    <p class="lead text-muted mb-4" data-mwplaceholder="Enter text here">Start your free trial today or talk to our team to find the plan that fits you best.</p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <module type="btn" id="{{ $params['id'] }}-cta4-btn-1" button_style="btn-primary" button_size="btn-lg px-5" text="Start free trial"/>
                        <module type="btn" id="{{ $params['id'] }}-cta4-btn-2" button_style="btn-outline-primary" button_size="btn-lg px-5" text="Contact sales"/>
                    </div>
                </div>
            </div>
        </x-col>
    </x-row>
</x-layout-section>
