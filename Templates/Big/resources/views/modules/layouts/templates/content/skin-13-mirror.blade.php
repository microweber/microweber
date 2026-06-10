@php
/*

type: layout

name: Content 13 Mirror

position: 13 mirror

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
    .content-13-images {
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
    }

    .content-13-images img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
    }

    @media (max-width: 1250px) {
        #{{ $params['id'] }} .container > .row > .col-12{
            width: 100% !important;
        }
    }

</style>

<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="container mw-layout-container safe-mode no-element   edit" field="layout-content-skin-13-mirror-{{ $params['id'] }}" rel="module">
        <div class="row ">
            <div class="col-12 col-sm-10 col-lg-6 mx-auto py-5 order-1 order-lg-2">
                <div class="d-flex flex-column ps-md-5 h-100   py-5 regular-mode">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Your Awesome Title</h3>

                    <p data-mwplaceholder="{{ _e('Enter text here') }}">Having used discount toner cartridges for twenty years, there have been a lot of changes in the toner cartridge market. The market today is approximately a twenty billion.</p>

                    <module type="btn" text="Lean more" button_style="btn-primary"/>
                </div>
            </div>

            <div class="col-12 col-sm-10 col-lg-6 mx-auto order-2 order-lg-1 safe-mode">
                <div class="col-md-12 text-end py-5">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row ">
                        <div class="m-4 d-flex d-sm-block allow-select">
                            <div class=" content-13-images w-250 h-250 cloneable element  ms-auto mb-6 me-3 me-sm-0" style="">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt="" style="width: 100%;height: 100%;object-fit: cover;"></div>

                            <div class=" content-13-images w-150 h-150 cloneable element  ms-auto">

                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt="" style="width: 100%;height: 100%;object-fit: cover;">
                            </div>
                        </div>

                        <div class="m-4 allow-select">
                            <div class=" content-13-images w-350 h-350 cloneable element " style="">
                                <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt="" style="width: 100%;height: 100%;object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
