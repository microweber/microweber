{{--
 type: layout
 name: Feature 55
 position: 55
 categories: Features
--}}

<style>
    .mw-features-55-clients-item-height {
        height: 120px;
    }

    .mw-features-55-clients-image {
        display: block;
        max-width: 100px;
        margin: auto;
        transition: all ease 0.2s;
    }

    .mw-features-55-clients-image:hover {
        transform: scale(1.3);
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-features-55-clients feature-55"
    :has-spacers="false"
    container-class="mw-layout-container no-element"
>
    <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
        <div class="container mw-layout-container no-element container edit" field="layout-feature-skin-55-{{ $params['id'] }}" rel="module">
            <x-row class="align-items-center">
                <div class="col-12">
                    <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="text-center font-weight-bold mb-5">Companies I've had worked</h4>
                </div>

                <div class="col-lg-2 cloneable element col-4 ms-auto mw-features-55-clients-item-height">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/clients/cachet.svg') }}" class="mw-features-55-clients-image" alt=""/>
                </div>

                <div class="col-lg-2 cloneable element col-4 mw-features-55-clients-item-height">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/clients/guitar-center.svg') }}" class="mw-features-55-clients-image" alt=""/>
                </div>

                <div class="col-lg-2 cloneable element col-4 mw-features-55-clients-item-height">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/clients/tokico.svg') }}" class="mw-features-55-clients-image" alt=""/>
                </div>

                <div class="col-lg-2 cloneable element col-4 mw-features-55-clients-item-height">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/clients/shopify.svg') }}" class="mw-features-55-clients-image" alt=""/>
                </div>

                <div class="col-lg-2 cloneable element col-4 me-auto mw-features-55-clients-item-height">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/clients/profil-rejser.svg') }}" class="mw-features-55-clients-image" alt=""/>
                </div>
            </x-row>
        </div>
        <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</x-layout-section>
