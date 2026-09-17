<?php
/*
type: layout
name: Testimonials 1 - Module
position: 1
categories: Testimonials
*/
?>
<x-layout-section
    :params="$params" :classes="$classes" :layout-classes="$layout_classes ?? ''"
    section-class="section testimonials-skin-1"
    field-name="layout-testimonials-skin-1"
>
    <x-row class="text-center">
        <x-col size="12" size-lg="8" size-xl="7" class="mx-auto">
            <x-section-heading tag="h2" subtitle="Real stories from people who use our product every day.">
                What our customers say
            </x-section-heading>
        </x-col>
    </x-row>

    <x-row class="mt-4">
        <x-col size="12">
            <module type="testimonials" id="{{ $params['id'] }}-testimonials" template="skin-1"/>
        </x-col>
    </x-row>
</x-layout-section>
