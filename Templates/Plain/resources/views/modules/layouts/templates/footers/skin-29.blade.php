{{--
type: layout
name: Footers 29
position: 29
categories: Footers
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="footer-skin-29 footer-background py-0"
    container-class="mw-layout-container no-element container-fluid"
>
    <div class="d-flex flex-column gap-2 justify-content-center edit" field="layout-footer-skin-29-{{ $params['id'] }}" rel="module">
                <div class="text-center">
                    <module type="logo" id="footer-logo-{{ $params['id'] }}" />
                </div>
                <div class="">
                    <module type="menu" class="footer-skin-link text-center" template="simple" name="footer_menu"/>
                </div>
                <div class="text-center">
                    <module type="social_links"/>
                </div>
            </div>
    <x-footer-section copyright-field="footer-reserved-skin-29" :section-id="$params['id']" />
</x-layout-section>
