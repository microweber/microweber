{{--
type: layout
name: Testimonial Cards
position: 26
categories: Testimonials
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-testimonials-skin-26"
    container-class="mw-layout-container no-element container edit"
>
    <div class="text-center mx-auto pb-4" style="max-width: 720px;">
                <h3>What Our Clients Say</h3>
                <p>Hear from the people who trust us with their business.</p>
            </div>
            <module type="testimonials" template="skin-23" project_name="Testimonials Cards"/>
</x-layout-section>
