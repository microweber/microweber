{{--
type: layout
name: Header 16
position: 16
categories: Header
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-dark-background py-0 d-flex align-items-center justify-content-center"
    :has-spacers="false"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="mw-layout-container no-element"
>
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-10.jpg') }}"/>
        <div class="mw-layout-container py-4 container mw-header-section-mh-100vh d-flex align-items-center justify-content-center no-element edit " field="layout-header-skin-16-{{ $params['id'] ?? '' }}" rel="module">
            <x-row>
                <div class="text-center">
                    <div class="allow-select">
                        <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title mb-7">Describe your company </h1>
                        <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>

                        <br>

                        <div class="text-center safe-mode d-flex align-items-center justify-content-center gap-2">
                            <module type="btn" button_style="btn-primary" text="Button For Your Banner"/>
                            <module type="btn" button_style="btn-outline-primary" text="Button For Your Banner"/>
                        </div>
                    </div>
                </div>
            </x-row>
        </div>
</x-layout-section>
