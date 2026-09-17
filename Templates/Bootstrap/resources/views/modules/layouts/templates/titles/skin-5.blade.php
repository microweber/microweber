<?php
/*
type: layout
name: Titles 5 - Display title with badges
position: 5
categories: Titles
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section titles-skin-5"
    field-name="layout-titles-skin-5"
>
    <x-row class="text-center">
        <x-col size-lg="9" class="mx-auto">
            <h1 class="display-4 fw-bold mb-3" data-mwplaceholder="Enter title here">Everything you need in one place</h1>
            <p class="lead text-muted mb-4" data-mwplaceholder="Enter text here">Bring your ideas together with tools that stay out of your way, so you can spend less time on setup and more time on the work that matters most.</p>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <a href="#" class="badge text-bg-primary text-decoration-none px-3 py-2">Get started</a>
                <a href="#" class="badge text-bg-secondary text-decoration-none px-3 py-2">Learn more</a>
            </div>
        </x-col>
    </x-row>
</x-layout-section>
