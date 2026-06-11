{{--
type: layout

name: Ecommerce 3

position: 3

categories: Ecommerce
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-ecommerce-skin-3"
    container-class="mw-layout-container no-element container edit"
>
    <module type="shop/products" template="skin-2" />
</x-layout-section>
