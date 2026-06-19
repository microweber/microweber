{{--
type: layout
name: Testimonial 12
position: 12
categories: Testimonials
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-testimonials-skin-12"
    container-class="mw-layout-container no-element container edit"
>
    <x-row class="text-center">
                <div class="col-12 col-lg-8 col-lg-6 mx-auto">
                    <h3>Testimonials</h3>
                    <p>Stu Unger is one of the biggest superstars to have emerged from the professional poker world.</p>
                    <br /><br /><br />
                </div>
            </x-row>
            <div></div>
            <module type="testimonials" template="skin-9" project_name="Testimonials 1"/>
</x-layout-section>
