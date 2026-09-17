<?php
/*
type: layout
name: Text block 2 - Two columns
position: 2
categories: Text block
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section text-block-skin-2"
    field-name="layout-text-block-skin-2"
>
    <x-row>
        <x-col size="12">
            <x-section-heading tag="h2" align="start">A modern workspace built around the way your team actually works</x-section-heading>
        </x-col>
    </x-row>
    <x-row>
        <x-col size="12" size-md="6">
            <p data-mwplaceholder="Enter text here">We started this project with a simple belief: good tools should get out of your way. Every feature we ship is measured against how quickly it lets someone finish a real task, not by how impressive it looks in a demo. That focus keeps the product lean and the learning curve short.</p>
            <p data-mwplaceholder="Enter text here">Over the past year we rebuilt the core editor from the ground up, cutting page load times almost in half. The result is an experience that feels immediate whether you are drafting a quick note or assembling a detailed report.</p>
        </x-col>
        <x-col size="12" size-md="6">
            <p data-mwplaceholder="Enter text here">Collaboration sits at the heart of everything. Comments, revisions and shared drafts all live in one place, so nobody has to dig through email threads to find the latest version of a document.</p>
            <p data-mwplaceholder="Enter text here">Security and reliability round out the picture. Your content is backed up continuously and encrypted at rest, which means you can spend your energy on the work itself rather than worrying about where it lives.</p>
        </x-col>
    </x-row>
</x-layout-section>
