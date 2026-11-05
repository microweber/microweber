{{--
type: layout
name: Footers 27
position: 27
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
        <div class="mw-layout-container no-element container edit" field="layout-footer-skin-27-{{ $params['id'] }}" rel="module">
            <x-row>
                <div class="col-12 row justify-content-md-center">
                    <div class="col-sm-6 col-12 text-md-start text-center">
                        <div class="mb-md-0 mb-5">
                            <module type="logo" id="footer-logo-{{ $params['id'] }}" />
                        </div>
                    </div>

                    <div class="col-sm-6 col-12 align-self-center">
                        <module type="contact_form" template="subscribe-6"/>
                    </div>
                </div>
            </x-row>
            <div class="text-md-start text-center">
                <div class="d-md-flex align-items-md-center px-md-0 mt-4 pb-4">
                    <div class="col-md-4 col">
                        <p class="font-weight-bold">Website Builder and CMS </p>
                        <br>
                        <small>CMS is a website builder and content management system of new generation.</small>
                        <br>
                    </div>
                    <div class="col-md-4 col">
                        <module type="menu" class="footer_skin" template="simple" name="footer_menu"/>
                    </div>
                    <div class="col-md-4 col text-md-end">
                        <module type="social_links"/>
                    </div>
                </div>
            </div>
        </div>
    <x-footer-section copyright-field="footer-reserved-skin-27" :section-id="$params['id']" />
</x-layout-section>
