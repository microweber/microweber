{{--
type: layout

name: Ecommerce 14

position: 14

categories: Ecommerce
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-ecommerce-skin-14"
    container-class="mw-layout-container no-element container edit"
>
    <x-row>
                <div class="col-lg-12 text-center">
                    <div class="section-heading">
                        <p>LATEST COURSES</p>
                        <h2>Latest Courses</h2>
                    </div>
                </div>
                <module type="shop" template="skin-1"/>
            </x-row>
</x-layout-section>
