@php
/*

type: layout

name: Content 43

position: 43

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
    <div class="container mw-layout-container safe-mode no-element    d-flex justify-content-center align-items-center edit" field="layout-content-skin-43-{{ $params['id'] }}" rel="module">
        <div class="text-center">
            <div class="row text-center ">
                <div class="col-12 col-lg-10 col-lg-8 mx-auto  regular-mode allow-select allow-drop">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Your Awesome Title</h3>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">Are you considering buying a compatible inkjet cartridge for your printer?
                        <br> There are many reputed companies like Canon, Epson, Dell, and Lexmark.</p>
                    <br />
                    <br />
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-4.jpg') }}" alt=""/>
                </div>
            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
