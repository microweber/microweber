@php
/*

type: layout

name: Content 41

position: 41

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

<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="container-fluid px-0 mw-layout-container safe-mode no-element   edit" field="layout-content-skin-41-{{ $params['id'] }}" rel="module">
        <div class="row mh-650">
            <div class="col-12 col-sm-10 col-lg-6 mx-auto mh-400  allow-select allow-drop" style="min-height: 100px">
                <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-15.jpg') }}" alt="">
            </div>

            <div class="col-12 col-sm-10 col-lg-6 mx-auto text-center text-lg-start d-flex align-items-center">

                <div class="col-md-10 mx-auto   text-center regular-mode allow-drop allow-select">
                    <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4  ">Look Up In The Sky</h5>
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">The Basics Of Buying A Telescope</h3>

                    <p data-mwplaceholder="{{ _e('Enter text here') }}">To ensure the blackest blacks and sharpest colors on every print job, the Eclipse OEM-compatible toner cartridges use just premium</p>
                    <br/>

                    <module type="btn" button_style="btn-primary" text="Learn More"/>
                </div>

            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
