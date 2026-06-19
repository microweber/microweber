{{--
type: layout
name: Footers 28
position: 28
categories: Footers
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="footer-background"
    container-class="mw-layout-container no-element"
>
    <!-- Footer -->
        <div class="mw-layout-container no-element container edit" field="layout-footer-skin-28-{{ $params['id'] }}" rel="module">
            <x-row class="justify-content-center">
                <div class="col-md-12 d-md-flex justify-content-md-center align-items-lg-center px-md-0 my-lg-5">
                    <div class="col-md-7 col">
                        <module type="menu" class="footer-skin-link text-center" template="simple" name="footer_menu"/>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <module type="social_links"/>
                </div>
            </x-row>
        </div>
    <x-footer-section copyright-field="footer-reserved-skin-28" :section-id="$params['id']" />
</x-layout-section>
