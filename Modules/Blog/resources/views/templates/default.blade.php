@php
/*
type: layout
name: Default
description: Default blog template
*/
@endphp

<div class="module-blog-template">
    @php
        $moduleId = $id ?? null;
    @endphp

    <div class="module-blog-wrapper">
        <livewire:module-blog :module-id="$moduleId" />
    </div>
</div>

<style>
.module-blog-template {
    width: 100%;
    max-width: 100%;
    margin: 0 auto;
    padding: 0;
    box-sizing: border-box;
}

.module-blog-wrapper {
    background: var(--background-color, #ffffff);
    border-radius: 8px;
    overflow: hidden;
}
</style>
