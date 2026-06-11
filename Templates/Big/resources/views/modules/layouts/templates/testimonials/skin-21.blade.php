{{--
type: layout
name: Testimonials - Parallax
position: 21
categories: Testimonials
--}}

<x-layout-section
    :has-background="false"
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section testimonials-21 mw-layout-dark-background d-flex mw-layout-parallax"
    default-padding-top="mw-p-t-80"
    default-padding-bottom="mw-p-b-80"
    container-class="mw-layout-container no-element"
>
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/sections/salmon_and_mashrooms.jpg') }}" id="background-layout--{{ $params['id'] }}" />
    <div class="mw-layout-container no-element container align-self-center {{ $layout_classes ?? '' }} edit" field="layout-skin-21-{{ $params['id'] }}" rel="module">
            <module type="testimonials" template="skin-15" project_name="Testimonials 1"/>
        </div>
</x-layout-section>
