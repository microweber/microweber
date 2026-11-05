{{--
type: layout
name: Header 5
position: 5
categories: Header
--}}

<x-layout-section
    :has-background="false"
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-dark-background py-0 d-flex align-items-center justify-content-center"
    :has-spacers="false"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="mw-layout-container no-element"
>
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" id="background-layout--{{ $params['id'] ?? '' }}" />
        <div class="mw-layout-container py-4 container-fluid mw-header-section-mh-100vh d-flex align-items-center justify-content-center no-element edit mw-pointer-skip" field="layout-header-skin-5-{{ $params['id'] ?? '' }}" rel="module">
            <x-row class="allow-select">
                <div class="col-12 safe-mode mx-auto">
                    <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title mb-7">Describe your company </h1>
                    <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>

                    <x-row class="d-flex justify-content-center mt-5">
                        <a href="#" class="px-0 w-150">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/content-39-1.jpg') }}" class="cloneable element" alt=""/>
                        </a>
                        <a href="#" class="px-0 w-150">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/content-39-2.jpg') }}" class="cloneable element" alt=""/>
                        </a>
                    </x-row>
                </div>

                <div class="position-absolute bottom-0 w-100 text-center left-0">
                    <a href="#" class="btn btn-outline-primary btn-sm mb-7">
                        <i class="mdi mdi-chevron-down icon-size-24px me-0"></i>
                    </a>
                </div>
            </x-row>
        </div>
</x-layout-section>
