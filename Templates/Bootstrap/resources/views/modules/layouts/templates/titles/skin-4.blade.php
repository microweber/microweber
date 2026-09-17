<?php
/*
type: layout
name: Titles 4 - Heading with divider line
position: 4
categories: Titles
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section titles-skin-4"
    field-name="layout-titles-skin-4"
>
    <x-row>
        <x-col size-lg="12">
            <div class="d-flex align-items-center mb-3">
                <h2 class="fw-bold mb-0 flex-shrink-0" data-mwplaceholder="Enter title here">Latest from the team</h2>
                <hr class="flex-grow-1 ms-3 border-top opacity-25">
            </div>
            <p class="text-muted mb-0" data-mwplaceholder="Enter text here">News, updates and stories from the people behind the work.</p>
        </x-col>
    </x-row>
</x-layout-section>
