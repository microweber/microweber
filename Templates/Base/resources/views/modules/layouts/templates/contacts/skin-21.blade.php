{{--
type: layout

name: Contacts 21

position: 21

categories: Contact Us
--}}

<style>
    .text-info {
        color: var(--mw-primary-color);
    }

    .venue-thumb {
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }

    .venue-info-title,
    .venue-info-body {
        padding: 40px;
    }

    .venue-info-title {
        background: var(--mw-primary-color);
        padding: 20px 40px;
    }

    .google-map iframe {
        border-radius: 20px;
    }
</style>

<x-layout-section
    :has-background="false"
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-parallax"
    container-class="mw-layout-container no-element"
>
    <module type="background" data-background-color="#F0F8FF" data-background-image="{{ asset('templates/big/img/sections/trees.jpg') }}" id="background-layout--{{ $params['id'] ?? '' }}" />

        <module type="spacer" height="120px" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />

            <div class="mw-layout-container no-element container edit safe-mode " field="layout-contacts-skin-21-{{ $params['id'] ?? '' }}" rel="module">
                <x-row>
                    <div class="col-lg-12 col-12 allow-select">
                        <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-5">Here you go <u class="text-info">Venue</u></h2>
                    </div>

                    <div class="col-lg-6 col-12 allow-select">
                        <module type="google_maps" class="google-map" />
                    </div>

                    <div class="col-lg-6 col-12 mt-5 mt-lg-0 allow-select">
                        <div class="venue-thumb bg-white shadow-lg">
                            <div class="venue-info-title background-color-element element">
                                <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="text-white mb-0">Times Square</h2>
                            </div>

                            <div class="venue-info-body background-color-element element">
                                <h4 class="d-flex">
                                    <i class="mw-micon-Location-2 me-2"></i>
                                    <span>102 South. 7th Street, New York, NY 10036, USA</span>
                                </h4>

                                <h5 class="mt-4 mb-3">
                                    <a href="mailto:hello@yourgmail.com">
                                        <i class="mw-micon-Email me-2"></i>
                                        hi@company.com
                                    </a>
                                </h5>

                                <h5 class="mb-0">
                                    <a href="tel: 305-240-9671">
                                        <i class="mw-micon-Telephone me-2"></i>
                                        010-020-0340
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>

                </x-row>
            </div>

        <module type="spacer" height="120px" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
</x-layout-section>
