@php
/*

type: layout

name: Content 8 - Parallax

position: 8

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


<section class="section mw-layout-dark-background mw-layout-parallax {{ $layout_classes }} ">
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container mh-450 d-flex align-items-center justify-content-center safe-mode no-element   edit " field="layout-content-skin-8-{{ $params['id'] }}" rel="module">
        <div class="container {{ $layout_classes }}">
            <div class="row text-center">
                <div class="col-12 col-lg-10 col-lg-8 mx-auto  allow-select allow-drop" style="min-height: 50px">
                    <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-3">Moon Gazing</p>
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Free Classifieds Using Them To Promote Your Stuff Online</h3>
                    <module class="mt-7 text-center" type="btn" button_style="btn-primary" button_size="btn-md" text="Learn more"/>
                </div>
            </div>


        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
