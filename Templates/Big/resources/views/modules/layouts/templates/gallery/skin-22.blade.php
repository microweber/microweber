{{--
type: layout
name: Gallery 22
description: Gallery 22
categories: Gallery
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section edit"
    :has-background="false"
    container-class="mw-layout-container no-element container-fluid px-0"
>
    <x-row>
                <div class="col-12">
                    <module type="pictures" template="skin-13"/>
                </div>
            </x-row>
</x-layout-section>
