{{--
type: layout
name: Testimonial 10
position: 10
categories: Testimonials
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-testimonials-skin-10"
    default-padding-bottom="pb-0"
    container-class="mw-layout-container no-element container edit"
>
    <x-row class="text-center">
                <div class="col-12 col-lg-10 col-lg-8 mx-auto">
                    <h6>Testimonials</h6>
                    <br /><br /><br />
                </div>
            </x-row>
            <div></div>
            <module type="testimonials" template="skin-5" project_name="Testimonials 1"/>
</x-layout-section>
