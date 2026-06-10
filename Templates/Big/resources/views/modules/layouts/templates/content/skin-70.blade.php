@php
/*

type: layout

name: Content 70 - Video Background

position: 70

categories: Content

*/
@endphp

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = 'p-t-100';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = 'p-b-100';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section mw-layout-dark-background">
    <module type="background" data-background-video="{{ template_url() }}video/layouts/content-video-1.mp4" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />

    <div class="container mw-layout-container safe-mode mh-100vh d-flex flex-column align-items-center justify-content-center no-element {{ $layout_classes }}   edit " field="layout-content-skin-70-{{ $params['id'] }}" rel="module">

       <div class="regular-mode allow-drop ">
           <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="header-section-title fx-deactivate">Your Awesome Title Here</h2>
           <p data-mwplaceholder="{{ _e('Enter text here') }}" class="header-section-p fx-deactivate">Leave application now and get -20% discount <br />for your first repair</p>
           <br/><br/><br/>

           <module type="btn" button_text="Get a Discount" class="fx-particles-1"/>
       </div>
    </div>

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
