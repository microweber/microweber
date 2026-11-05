{{--
type: layout
name: Header 12
position: 12
categories: Header
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section py-0 d-flex align-items-center justify-content-center"
    field-name="layout-header-skin-12"
    :has-spacers="false"
    default-padding-top="pt-7"
    default-padding-bottom="pb-0"
    container-class="mw-layout-container py-4 container mw-header-section-mh-100vh d-flex align-items-center justify-content-center no-element edit"
>
    <x-row class="text-center">
                <div class="col-12 safe-mode col-lg-8 mx-auto">
                    <div class="allow-select">
                        <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title mb-7">Describe your company </h1>
                        <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>

                        <div class="d-flex justify-content-center mt-4 mb-4">
                            <module type="contact_form" template="subscribe-1" />
                        </div>

                        <small>Your data will not be shared with third parties</small>
                        <br/> <br/><br/><br/>

                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" style="max-width: 70%;" alt=""/>
                    </div>
                </div>
            </x-row>
</x-layout-section>
