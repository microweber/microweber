@php
/*

type: layout

name: Content 30

position: 30

categories: Content

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = 'pt-10';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'pb-10';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp


<section class="section mw-layout-dark-background" data-parallax-x="true" data-overlay-black="true" data-overlay="2">

    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" id="background-layout--{{ $params['id'] }}" />

    <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] }}-top" />

    <div class="container mw-layout-container safe-mode   mw-layout-overlay-container {{ $layout_classes }} edit" field="layout-content-skin-30-{{ $params['id'] }}" rel="module">
        <div class="row  background-color-element ">
            <div class="col-12 col-sm-10 col-lg-8 col-lg-6   ">
                <div class="regular-mode allow-drop allow-select">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-3">Make Money Online Through Advertising</h3>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">Planning to visit Las Vegas or any other vacational resort where casinos are a major portion of their business? I have just the thing for you</p>
                </div>
                <div class="row mt-8 ">
                    <div class="col-md-6  regular-mode allow-drop allow-select">
                        <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Las Vegas How To Have Non Gambling Related Fun</h5>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">According to the research firm Frost & Sullivan, the estimated size of the North American </p>
                    </div>

                    <div class="col-md-6  regular-mode allow-drop allow-select">
                        <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Stu Unger Rise And Fall Of A Poker Genius</h5>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">According to the research firm Frost & Sullivan, the estimated size of the North American </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
