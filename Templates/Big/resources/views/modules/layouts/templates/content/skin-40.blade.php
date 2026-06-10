@php
/*

type: layout

name: Content 40

position: 40

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

<section class="section {{ $layout_classes }} " data-bg-contain="true" background-position="left center">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="container-fluid mw-layout-container safe-mode no-element   edit safe-mode" field="layout-content-skin-40-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 col-md-6 mx-auto pb-5 mb-4 pe-lg-5 text-center text-lg-start d-flex align-items-center order-2 order-lg-1">
                <div class=" text-center ">
                    <div class=" regular-mode  allow-drop allow-select">

                        <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Your Title Here</h3>

                        <p data-mwplaceholder="{{ _e('Enter text here') }}">To ensure the blackest blacks and sharpest colors on every print job, the Eclipse OEM-compatible toner cartridges use just premium</p>

                        <module type="btn" button_style="btn-primary" button_size="btn-md px-5" text="BUTTON"/>



                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="d-block mb-4">Follow us</p>

                        <module type="social_links" />
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 order-1 order-lg-2 mb-4 allow-drop allow-select">
                <module type="slider"/>
            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
