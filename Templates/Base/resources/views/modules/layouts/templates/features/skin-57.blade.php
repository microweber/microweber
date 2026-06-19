{{--
 type: layout
 name: Feature 57
 position: 57
 categories: Features
--}}

<style>
    .mw-features-57-clients-item-wrapper {
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all ease 0.6s;
    }

    .mw-features-55-clients-image {
        display: block;
        max-width: 100px;
        height: 100%;
        margin: auto;
        transition: all ease 0.2s;
    }

    .mw-features-57-clients-item-wrapper:hover {
        transition: all ease 0.6s;
        background-color: rgba(236, 236, 236, 0.64);
    }

    .mw-features-57-clients-item-wrapper:hover .mw-features-55-clients-image {
        transform: scale(1.02);
        transition: all ease 0.6s;
    }

    .mw-features-55-clients-image:hover {
        transform: scale(1.05);
        transition: all ease 0.6s;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-features-55-clients feature-57"
    :has-spacers="false"
    container-class="mw-layout-container no-element"
>
    <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
        <div class="container mw-layout-container no-element container edit" field="layout-feature-skin-57-{{ $params['id'] }}" rel="module">
            <x-row class="align-items-center">
                <div class="col-lg-3 cloneable element col-md-6 col-12 ms-auto mw-features-57-clients-item-wrapper">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/clients/cachet.svg') }}" class="mw-features-55-clients-image" alt=""/>
                </div>

                <div class="col-lg-3 cloneable element col-md-6 col-12 mw-features-57-clients-item-wrapper">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/clients/guitar-center.svg') }}" class="mw-features-55-clients-image" alt=""/>
                </div>

                <div class="col-lg-3 cloneable element col-md-6 col-12 mw-features-57-clients-item-wrapper">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/clients/tokico.svg') }}" class="mw-features-55-clients-image" alt=""/>
                </div>

                <div class="col-lg-3 cloneable element col-md-6 col-12 mw-features-57-clients-item-wrapper">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/clients/shopify.svg') }}" class="mw-features-55-clients-image" alt=""/>
                </div>
            </x-row>
        </div>
        <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</x-layout-section>
