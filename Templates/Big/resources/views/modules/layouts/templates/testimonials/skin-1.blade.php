{{--
  type: layout
  name: Testimonial 1
  position: 1
  categories: Testimonials
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-container"
    field-name="layout-testimonials-skin-1"
    container-class="mw-layout-container no-element no-drop container edit noedit"
>
    <div>
                <module type="testimonials" template="skin-2" project_name="Testimonials 1"/>
            </div>
</x-layout-section>
