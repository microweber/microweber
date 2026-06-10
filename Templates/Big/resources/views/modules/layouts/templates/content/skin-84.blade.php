@php
    /*

    type: layout

    name: Content 84

    position: 84

    categories: Content

    */
@endphp

@php
    if (!isset($classes['padding_top'])) {
        $classes['padding_top'] = '';
    }
    if (!isset($classes['padding_bottom'])) {
        $classes['padding_bottom'] = '';
    }

    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .portfolio-image {
        display: block;
        transition: transform 0.6s ease-out;
    }

    .portfolio-thumb {
        position: relative;
        overflow: hidden;
    }

    .portfolio-thumb:hover .portfolio-image {
        transform: scale(1.02);
    }
</style>

<section class="section mw-content-84-about {{ $layout_classes }} section-content-83 pb-0">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />

    <div class="mw-layout-container safe-mode no-element edit container " field="layout-content-skin-84-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 allow-select">
                <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-5 text-center">Portfolio</h2>
            </div>

            <div class="col-lg-6 col-12 allow-select">
                <div class="portfolio-thumb cloneable mb-5">
                    <a class="image-popup">
                        <img loading="lazy" class="portfolio-image" src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt=""/>
                    </a>
                    <div class="portfolio-info">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="portfolio-title mt-3 mb-0">Effortless</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="text-danger">Branding</p>
                    </div>
                </div>

                <div class="portfolio-thumb cloneable">
                    <a class="image-popup">
                        <img loading="lazy" class="portfolio-image" src="{{ asset('templates/big/img/layouts/gallery-1-8.jpg') }}" alt=""/>
                    </a>
                    <div class="portfolio-info">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="portfolio-title mt-3 mb-0">Health technology</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="text-success">Art Direction</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-12 allow-select">
                <div class="portfolio-thumb cloneable mt-5 mt-lg-0 mb-5">
                    <a class="image-popup">
                        <img loading="lazy" class="portfolio-image" src="{{ asset('templates/big/img/layouts/gallery-1-12.jpg') }}" alt=""/>
                    </a>
                    <div class="portfolio-info">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="portfolio-title mt-3 mb-0">Maki</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="text-warning">Website</p>
                    </div>
                </div>

                <div class="portfolio-thumb cloneable allow-select">
                    <a class="image-popup">
                        <img loading="lazy" class="portfolio-image" src="{{ asset('templates/big/img/layouts/gallery-1-7.jpg') }}" alt=""/>
                    </a>
                    <div class="portfolio-info">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="portfolio-title mt-3 mb-0">The gig economy</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="text-info">Graphic</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
