{{--
type: layout

name: Ecommerce 8

position: 8

categories: Ecommerce
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-ecommerce-skin-8"
    container-class="mw-layout-container no-element container edit"
>
    <module type="shop/products" template="skin-7" />
</x-layout-section>
