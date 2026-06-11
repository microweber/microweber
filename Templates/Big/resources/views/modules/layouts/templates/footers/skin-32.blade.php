{{--
type: layout
name: Footers 32
position: 32
categories: Footers
--}}

<style>
    .footer-19-menu ul li a:first-child{
        padding-left: 0;
    }

    .footer-19-menu ul{
        display: flex;
        flex-wrap: wrap;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="footer-background py-0"
    field-name="layout-footer-skin-32"
    container-class="mw-layout-container no-element container-fluid edit"
>
    <x-row>
        <div class="edit mb-7 text-center" field="layout-footer-skin-19-title-{{ $params['id'] }}" rel="module">
            <h2 class="font-weight-bold">You have an idea? Let's Talk!</h2>
        </div>

        <div class="col-lg-5 text-md-start text-md-left">
            <div class="edit" field="layout-footer-skin-19-phone{{ $params['id'] }}" rel="module">
                <small> Phone </small>
                <p class="mt-2">123-456-7890</p>
            </div>
            <div class="edit mb-5" field="layout-footer-skin-19-email{{ $params['id'] }}" rel="module">
                <small> Email </small>
                <p class="mt-2"><a href="">mail@yourcompany.com</a></p>
            </div>
            <div class="edit" field="layout-footer-skin-19-social{{ $params['id'] }}" rel="module">
                <x-social-links>
                    <module type="social_links" template="skin-9"/>
                </x-social-links>
            </div>
        </div>

        <div class="col-lg-7 mt-lg-0 mt-5 row">
            <div class="col-md-6 me-auto d-flex justify-content-start">
                <module type="menu" class="text-center" template="simple" name="footer_menu"/>
            </div>

            <div class="col-md-6 mt-md-0 mt-4">
                <module type="contact_form" template="skin-3"/>
            </div>
        </div>
    </x-row>
</x-layout-section>

<x-footer-section copyright-field="footer-reserved-skin-19" :section-id="$params['id']" />
