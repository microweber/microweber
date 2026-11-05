{{--
type: layout
name: Gallery 23
description: Gallery 23
categories: Gallery
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-skin-23"
    :has-background="false"
    default-padding-top="p-t-70"
    default-padding-bottom="p-b-70"
    container-class="mw-layout-container no-element container-fluid px-0 edit"
>
    <x-row>
                <div class="col-12">
                    <module type="pictures" template="skin-3-guest"/>
                </div>
            </x-row>
</x-layout-section>
