{{--
type: layout
name: Header 35 - Slider
position: 35
categories: Header
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section py-0 d-flex align-items-center justify-content-center"
    field-name="layout-header-skin-35"
    :has-spacers="false"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="mw-layout-container py-4 container mw-header-section-mh-100vh d-flex align-items-center justify-content-center no-element edit"
>
    <div class="col-12 col-lg-10 allow-select mx-auto">
                <module type="slider" templaet="swiper-skin-1" />
            </div>
</x-layout-section>
