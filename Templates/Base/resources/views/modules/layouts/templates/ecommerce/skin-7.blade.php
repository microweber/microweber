{{--
type: layout

name: Ecommerce 7

position: 7

categories: Ecommerce
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-ecommerce-skin-7"
    container-class="mw-layout-container no-element container edit"
>
    <module type="shop/products" template="skin-6" />
</x-layout-section>
