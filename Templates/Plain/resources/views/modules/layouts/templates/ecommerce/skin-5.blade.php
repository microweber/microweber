{{--
type: layout

name: Ecommerce 5

position: 5

categories: Ecommerce
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-ecommerce-skin-5"
    container-class="mw-layout-container no-element container edit"
>
    <module type="shop/products" template="skin-4" slides-md="1" slides-lg="2" adaptive_height="false" arrows="true" pager="false" />
</x-layout-section>
