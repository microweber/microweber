@php
/*
type: layout
name: Default
description: Default Search template
*/
@endphp

@include('modules.search::components.custom-css')



<div class="module-search-template">
    @php
        $moduleId = $id ?? null;
    @endphp


    <script>mw.moduleCSS("<?php print asset('modules/search/css/search.css'); ?>", true);</script>
    <script>mw.moduleJS("<?php print asset('modules/search/js/search.js'); ?>", true);</script>


    <div class="module-search-wrapper">
        <livewire:module-search :module-id="$moduleId" />
    </div>
</div>


