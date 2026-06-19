{{--
type: layout
name: Testimonial 25
position: 25
categories: Testimonials
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section pb-5"
    field-name="layout-testimonials-skin-25"
    container-class="mw-layout-container no-element container-fluid edit"
>
    <x-row class="col-12 col-xl-10 mx-auto">
                <h2 class="mb-5">Don’t Believe <br> Me Check Client Says</h2>
                <div></div>
                <module type="testimonials" template="skin-20" project_name="Testimonials 1"/>
            </x-row>
</x-layout-section>
