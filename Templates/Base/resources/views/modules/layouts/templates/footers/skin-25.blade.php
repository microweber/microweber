{{--
type: layout
name: Footers 25
position: 25
categories: Footers
--}}

<style>
    .footer-skin-25 .white-color {
        color: #a3a3a3;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="footer-skin-25 footer-background py-0"
    field-name="layout-footer-skin-25"
    :use-container="false"
>
    <div class="mw-layout-container no-element container-fluid">
        <x-row class="justify-content-center">
            <div class="col-md-12 d-md-flex justify-content-md-center align-items-lg-center mt-lg-7">
                <div class="col-md-4 col text-md-start text-center">
                    <module type="logo" id="footer-logo-{{ $params['id'] }}" />
                </div>
                <div class="col-md-4 col">
                    <module type="menu" class="footer-skin-link text-center" template="simple" name="footer_menu"/>
                </div>
                <div class="col-md-4 col text-md-end text-center">
                    <x-social-links />
                </div>
            </div>
        </x-row>
    </div>
</x-layout-section>

<x-footer-section copyright-field="footer-reserved-skin-25" :section-id="$params['id']" />
