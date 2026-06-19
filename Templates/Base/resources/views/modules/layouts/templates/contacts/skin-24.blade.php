{{--
type: layout

name: Contacts 24

position: 24

categories: Contact Us
--}}

<style>
    .contacts-24 .info-item {
        background: #f4f4f4;
        padding: 30px;
        height: 100%;
    }

    .contacts-24 .info-item .icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 56px;
        height: 56px;
        font-size: 24px;
        line-height: 0;
        color: #fff;
        background: var(--mw-primary-color);
        border-radius: 50%;
        margin-right: 15px;
    }

    .contacts-24 .info-item h4 {
        margin: 0 0 5px 0;
    }

    .contacts-24 .info-item p {
        padding: 0;
        margin: 0;
    }

    @keyframes animate-loading {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .contacts-24 .mw_form {
        width: 100%;
        margin-top: 30px;
        background: #fff;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section contacts-24"
    field-name="layout-contacts-skin-24"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <div class="section-header text-center">
                    <p data-mwplaceholder="{{ _e('Enter title here') }}">Contact</p>
                    <h2 data-mwplaceholder="{{ _e('Enter title here') }}">Need Help? <span>Contact Us</span></h2>
                </div>

                <div class="mb-3">
                    <module type="google_maps"/>
                </div><!-- End Google Maps -->

                <x-row class="gy-4">
                    <div class="col-md-6 cloneable element">
                        <div class="info-item background-color-element d-flex align-items-center">
                            <i class="icon mw-micon-Map2 flex-shrink-0 background-color-element element"></i>
                            <div>
                                <h4>Our Address</h4>
                                <p>A108 Adam Street, New York, NY 535022</p>
                            </div>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="col-md-6 cloneable element">
                        <div class="info-item background-color-element d-flex align-items-center">
                            <i class="icon mw-micon-Email flex-shrink-0 background-color-element element"></i>
                            <div>
                                <h4>Email Us</h4>
                                <p>contact@example.com</p>
                            </div>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="col-md-6 cloneable element">
                        <div class="info-item background-color-element d-flex align-items-center">
                            <i class="icon mw-micon-Telephone flex-shrink-0 background-color-element element"></i>
                            <div>
                                <h4>Call Us</h4>
                                <p>+1 5589 55488 55</p>
                            </div>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="col-md-6 cloneable element">
                        <div class="info-item background-color-element d-flex align-items-center">
                            <i class="icon mw-micon-Network flex-shrink-0 background-color-element element"></i>
                            <div>
                                <h4>Opening Hours</h4>
                                <div><strong>Mon-Sat:</strong> 11AM - 23PM;
                                    <strong>Sunday:</strong> Closed
                                </div>
                            </div>
                        </div>
                    </div><!-- End Info Item -->

                </x-row>

                <module type="contact_form" template="skin-1"/>
</x-layout-section>
