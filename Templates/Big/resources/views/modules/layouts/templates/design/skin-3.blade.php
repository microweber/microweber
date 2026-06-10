{{--
 type: layout
 name: Design 3
 position: 103
 categories: Design
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .heading-two {
        color: #222;
        font-size: 45px;
        font-weight: 400;
        letter-spacing: -.01em;
        line-height: 1.15em;
    }

    .horizontal-line {
        background-color: rgba(34, 34, 34, .1);
        height: 1px;
        width: 100%;
    }

    .values-image {
        max-height: 700px;
    }

    @media screen and (min-width: 1200px) {
        .values-image {
            max-width: 429px;
        }
    }

    .values-list {
        column-gap: 30px;
        row-gap: 30px;
    }

    .values-item {
        column-gap: 24px;
        display: grid;
        grid-auto-columns: 1fr;
        grid-template-columns: 1fr 2fr;
        grid-template-rows: auto;
        row-gap: 24px;
        padding: 30px 0;
    }

    @media screen and (max-width: 991px) {
        .heading-two {
            font-size: 42px;
        }
    }

    @media screen and (max-width: 767px) {
        .heading-two {
            font-size: 36px;
        }
    }

    @media screen and (max-width: 479px) {
        .heading-two {
            font-size: 28px;
        }
    }
</style>

<section class="{{ $layout_classes }} section mw-new-layouts-3">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>

    <div class="container mw-layout-container no-element edit safe-mode"
         field="layout-new-layouts-skin-3-{{ $params['id'] }}" rel="module">

        <div class="row justify-content-between">
            <div class="col-lg-6">
                <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="heading-two">Our Values</h2>
                <div class="values-list">
                    <div class="values-item">
                        <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-bold">Integrity</h6>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Upholding honesty and ethical practices in every aspect, ensuring trust and reliability in all of CMS's interactions and decisions.</p>
                    </div>
                    <div class="horizontal-line"></div>
                    <div class="values-item">
                        <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-bold">Innovation</h6>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Dedicated to continuous evolution, CMS simplifies legal processes with advanced technology, setting new standards in the legal tech landscape.</p>
                    </div>
                    <div class="horizontal-line"></div>
                    <div class="values-item">
                        <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-bold">Accessibility</h6>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Committed to making legal support attainable and user-friendly, CMS ensures businesses of all sizes have easy access to essential legal services.</p>
                    </div>
                    <div class="horizontal-line"></div>
                    <div class="values-item">
                        <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="font-weight-bold">Collaboration</h6>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">CMS believes in the power of teamwork and partnerships, working closely with clients and legal professionals to develop more effective legal solutions.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-end mt-lg-0 mt-3">
                <img loading="lazy" class="values-image" src="{{ asset('templates/big/img/layouts/gallery-1-vertical.jpg') }}" alt=""/>
            </div>
        </div>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>
