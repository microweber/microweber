{{--
type: layout

name: Ecommerce 1

position: 1

categories: Ecommerce
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-ecommerce-skin-1"
    container-class="mw-layout-container no-element container-fluid edit"
>
    <module type="shop/products" template="default" />
</x-layout-section>
