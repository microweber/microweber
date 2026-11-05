{{--
type: layout
name: Footers 9
position: 9
categories: Footers
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="footer-background py-0"
    container-class="mw-layout-container no-element"
>
    <!-- Footer -->
        <div class="mw-layout-container no-element container edit" field="layout-footer-skin-9-{{ $params['id'] }}" rel="module">
            <x-row class="justify-content-center">
                <div class="col-md-12 d-md-flex justify-content-md-center align-items-lg-center mt-lg-7 px-md-5">
                    <div class="col-md-2 col text-md-start text-center">
                        <div class="pb-2">
                            <module type="logo" id="footer-logo-{{ $params['id'] }}" />
                        </div>
                    </div>
                    <div class="col-md-7 col">
                        <module type="menu" class="footer-skin-link text-center" template="simple" name="footer_menu"/>
                    </div>
                    <div class="col-md-3 col text-md-end text-center">
                        <module type="social_links"/>
                    </div>
                </div>
            </x-row>
        </div>
    <x-footer-section copyright-field="footer-reserved-skin-9" :section-id="$params['id']" />
</x-layout-section>
