{{--
type: layout

name: Ecommerce 10

position: 10

categories: Ecommerce
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-ecommerce-skin-10"
    container-class="mw-layout-container no-element container edit"
>
    <module type="shop/products" template="skin-10" />
</x-layout-section>
