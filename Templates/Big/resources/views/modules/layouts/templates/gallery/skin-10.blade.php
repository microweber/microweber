{{--
type: layout
name: Gallery 10
position: 10
categories: Gallery
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-gallery-skin-10"
    :has-background="false"
    container-class="mw-layout-container no-element container edit"
>
    <x-row>
                <div class="col-lg-10 mx-auto">
                    <module type="pictures" template="skin-4"/>
                </div>
            </x-row>
</x-layout-section>
