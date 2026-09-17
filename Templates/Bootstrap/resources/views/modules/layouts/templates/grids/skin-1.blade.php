<?php
/*
type: layout
name: Grids 1 - Content + Media
position: 1
categories: Grids
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section grids-skin-1"
    field-name="layout-grids-skin-1"
>
    <div class="row align-items-center g-5">
        <div class="col-12 col-lg-6">
            <h2 class="fw-bold mb-3" data-mwplaceholder="Enter title here">Build faster with a system that scales</h2>
            <p class="lead text-muted mb-4" data-mwplaceholder="Enter text here">Everything you need to launch, manage and grow — in one place. Ship pages in minutes and keep full control of your content.</p>
            <ul class="list-unstyled mb-4">
                <li class="d-flex align-items-start mb-3">
                    <i class="mw-micon-Speed-Fast fs-4 text-primary me-3"></i>
                    <span data-mwplaceholder="Enter text here">Lightning-fast performance out of the box</span>
                </li>
                <li class="d-flex align-items-start mb-3">
                    <i class="mw-micon-Shield-Protected fs-4 text-primary me-3"></i>
                    <span data-mwplaceholder="Enter text here">Secure by default with automatic backups</span>
                </li>
                <li class="d-flex align-items-start mb-3">
                    <i class="mw-micon-Globe-Earth fs-4 text-primary me-3"></i>
                    <span data-mwplaceholder="Enter text here">Reach a global audience with a built-in CDN</span>
                </li>
            </ul>
            <module type="btn" button_style="btn-primary" button_size="px-4" text="Get started" id="{{ $params['id'] }}-grids-btn1"/>
        </div>
        <div class="col-12 col-lg-6">
            <div class="ratio ratio-4x3 bg-light rounded"></div>
        </div>
    </div>
</x-layout-section>
