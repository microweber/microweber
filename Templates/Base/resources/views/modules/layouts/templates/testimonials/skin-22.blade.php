{{--
type: layout
name: Testimonial 22
position: 22
categories: Testimonials
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-tony-testimonials-22"
    default-padding-top="p-t-70"
    default-padding-bottom="p-b-70"
    container-class="mw-layout-container no-element container-fluid edit"
>
    <x-row class="justify-content-center">
                <div class="col-xl-10 ps-md-4 ms-md-1 py-5">
                    <h2>Loved by businesses, and <br> individuals</h2>
                </div>
                <div class="col-xl-10 d-xl-flex flex-xl-wrap">
                    <module type="testimonials" template="skin-16" style="max-width: 100%;" project_name="Testimonials 1"/>
                </div>
            </x-row>
</x-layout-section>
