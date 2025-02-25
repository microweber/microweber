@php
/*
type: layout
name: Autocomplete
description: Autocomplete Search template
*/
@endphp

<div class="module-search-template">
    @php
        $moduleId = $id ?? null;
    @endphp

    <script>mw.moduleCSS("<?php print asset('modules/search/css/search.css'); ?>", true);</script>
    <script>mw.moduleJS("<?php print asset('modules/search/js/search.js'); ?>", true);</script>



    <div class="module-search-wrapper">
        <livewire:module-search :module-id="$moduleId" :autocomplete="true" />
    </div>
</div>
