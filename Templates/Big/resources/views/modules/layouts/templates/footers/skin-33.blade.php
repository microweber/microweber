{{--
type: layout
name: Footers 33
position: 33
categories: Footers
--}}

<style>
    .footer-33 .icon {
        margin-right: 15px;
        font-size: 24px;
        line-height: 0;
        margin-top: 23px;
    }

    .footer-33 h4 {
        font-weight: bold;
        position: relative;
        padding-bottom: 5px;
    }

    .footer-33 .footer-links {
        margin-bottom: 30px;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="footer-background footer-33 py-0"
    field-name="layout-footer-skin-33"
    container-class="mw-layout-container no-element container-fluid edit"
>
    <x-row class="gy-3">
        <div class="col-lg-3 col-md-6 d-flex justify-content-center">
            <i class="mw-micon-Location-2 icon"></i>
            <div>
                <h4>Address</h4>
                <p>
                    A108 Adam Street <br>
                    New York, NY 535022 - US<br>
                </p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 footer-links d-flex justify-content-center">
            <i class="mw-micon-Telephone icon"></i>
            <div>
                <h4>Reservations</h4>
                <p>
                    <strong>Phone:</strong> +1 5589 55488 55<br>
                    <strong>Email:</strong> info@example.com<br>
                </p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 footer-links d-flex justify-content-center">
            <i class="mw-micon-Clock-Forward icon"></i>
            <div>
                <h4>Opening Hours</h4>
                <p>
                    <strong>Mon-Sat: 11AM</strong> - 23PM<br>
                    Sunday: Closed
                </p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 footer-links">
            <h4>Follow Us</h4>
            <div class="social-links d-flex">
                <module type="social_links" template="skin-9"/>
            </div>
        </div>
    </x-row>
</x-layout-section>

<x-footer-section copyright-field="footer-reserved-skin-19" :section-id="$params['id']" />
