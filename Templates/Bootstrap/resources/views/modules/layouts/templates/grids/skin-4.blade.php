<?php
/*
type: layout
name: Grids 4 - Alternating Rows
position: 4
categories: Grids
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section grids-skin-4"
    field-name="layout-grids-skin-4"
>
    <div class="row align-items-center g-5 mb-5">
        <div class="col-12 col-md-6">
            <div class="ratio ratio-4x3 bg-light rounded"></div>
        </div>
        <div class="col-12 col-md-6">
            <h3 class="fw-bold mb-3" data-mwplaceholder="Enter title here">Design without limits</h3>
            <p class="text-muted mb-4" data-mwplaceholder="Enter text here">Drag, drop and arrange your content exactly how you want it. Every block is fully editable, so your pages always match your vision.</p>
            <module type="btn" button_style="btn-primary" button_size="px-4" text="Explore features" id="{{ $params['id'] }}-grids-btn1"/>
        </div>
    </div>

    <div class="row align-items-center g-5 flex-md-row-reverse">
        <div class="col-12 col-md-6">
            <div class="ratio ratio-4x3 bg-light rounded"></div>
        </div>
        <div class="col-12 col-md-6">
            <h3 class="fw-bold mb-3" data-mwplaceholder="Enter title here">Publish with confidence</h3>
            <p class="text-muted mb-4" data-mwplaceholder="Enter text here">Preview changes in real time and go live with a single click. Roll back anytime — your work is always saved and secure.</p>
            <module type="btn" button_style="btn-primary" button_size="px-4" text="Start publishing" id="{{ $params['id'] }}-grids-btn2"/>
        </div>
    </div>
</x-layout-section>
