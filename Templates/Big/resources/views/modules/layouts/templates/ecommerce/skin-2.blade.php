{{--
type: layout

name: Ecommerce 2

position: 2

categories: Ecommerce
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-ecommerce-skin-2"
    container-class="mw-layout-container no-element container edit"
>
    <module type="shop/products" template="skin-1" />
</x-layout-section>
