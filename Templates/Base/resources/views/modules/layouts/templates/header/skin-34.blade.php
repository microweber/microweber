{{--
type: layout
name: Header 34 - Shape
position: 34
categories: Header
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section py-0"
    field-name="layout-header-skin-34"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="h-100 w-100 overflow-hidden position-absolute top-0 right-0 edit safe-mode"
>
    <div style="background-image: url({{ asset('templates/big/img/layouts/gallery-1-4.jpg') }});" class="header-34-mask">
            </div>
        </div>
        <div class="header-34-header-vh d-flex align-items-center">
            <div class="container header-34-header d-flex align-items-center">
                <x-row class="flex-lg-nowrap col-12 ms-0">
                    <div class="col-12 col-xs-10 col-lg-6 pe-lg-5 text-center text-lg-start d-flex align-items-center justify-content-center justify-content-lg-start z-index-header-34-text">
                        <div class="allow-drop text-white">
                            <h3>
                                <span style="color: #f2a900;">
                                    @if (!is_post())
                                        {{ page_title() }}
                                    @endif
                                </span>
                            </h3>
                        </div>
                    </div>
                </x-row>
            </div>
</x-layout-section>
