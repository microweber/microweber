{{--
type: layout
name: Ecommerce Product Cards
position: 15
categories: Ecommerce
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-ecommerce-skin-15"
    container-class="mw-layout-container no-element container edit"
>
    <module type="shop/products" template="skin-12"/>
</x-layout-section>
