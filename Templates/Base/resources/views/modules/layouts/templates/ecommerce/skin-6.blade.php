{{--
type: layout

name: Ecommerce 6

position: 6

categories: Ecommerce
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-ecommerce-skin-6"
    container-class="mw-layout-container no-element container edit"
>
    <module type="shop/products" template="skin-5" slides-md="2" slides-lg="3" adaptive_height="false" arrows="true" pager="false"/>
</x-layout-section>
