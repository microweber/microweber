{{--
type: layout
name: Footers 26
position: 26
categories: Footers
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="footer-skin-26 footer-background py-0"
    container-class="mw-layout-container no-element"
>
    <!-- Footer -->
        <div class="mw-layout-container no-element container edit" field="layout-footer-skin-26-{{ $params['id'] }}" rel="module">
            <x-row class="justify-content-center">
                <div class="col-md-8 text-center px-md-0 mt-lg-7">
                    <h2 class="white-color">I’d love to show you how this can transform your life.</h2>
                    <module type="btn" button_text="Book a consultation" class="d-flex justify-content-center my-5 btn-md"/>
                </div>

                <div class="col-12 text-center">
                    <module type="social_links"/>
                </div>
            </x-row>
        </div>
    <x-footer-section copyright-field="footer-reserved-skin-26" :section-id="$params['id']" />
</x-layout-section>
