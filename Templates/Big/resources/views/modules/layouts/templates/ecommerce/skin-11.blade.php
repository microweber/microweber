{{--
type: layout

name: Ecommerce 11

position: 11

categories: Ecommerce
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-ecommerce-skin-11"
    container-class="mw-layout-container no-element container edit"
>
    <module type="shop/products" template="skin-11" />
</x-layout-section>
