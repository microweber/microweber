{{--
type: layout
name: Header 20
position: 20
categories: Header
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section py-0 d-flex align-items-center justify-content-center"
    field-name="layout-header-skin-16"
    :has-spacers="false"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="mw-layout-container py-4 container mw-header-section-mh-100vh d-flex align-items-center justify-content-center no-element edit"
>
    <x-row>
                <div class="col-12 safe-mode col-sm-10 col-lg-6 me-auto mb-5">
                    <img loading="lazy" class="allow-select" src="{{ asset('templates/big/img/layouts/gallery-1-14.jpg') }}" alt="" />
                </div>

                <div class="col-12 safe-mode col-sm-10 col-lg-6 text-center ms-auto text-lg-start d-flex align-items-center mb-5">
                    <div class="allow-select">
                        <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title mb-7">Describe your company </h1>
                        <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>

                        <br/>
                        <module type="btn" button_style="btn-primary" button_size="btn-lg px-5" text="Read More"/>
                    </div>
                </div>
            </x-row>
</x-layout-section>
