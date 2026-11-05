{{--
type: layout

name: Ecommerce 12

position: 12

categories: Ecommerce
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-ecommerce-skin-12"
    container-class="mw-layout-container no-element container-fluid edit"
>
    <x-row>
                <div class="col-xl-10 justify-content-center align-items-center text-center mx-auto">
                    <h3 class="my-3">We Provide Many Types Of Course</h3>
                    <p class="mb-5">Price pattern glossy waistine ensemnle trend pumps petticoat sewing pretportrt <br> value young availability original hondbong influence</p>

                    <module type="shop/products" template="skin-12"/>
                </div>
            </x-row>
</x-layout-section>
