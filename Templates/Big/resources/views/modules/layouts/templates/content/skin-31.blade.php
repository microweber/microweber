@php
/*

type: layout

name: Content 31

position: 31

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
    <div class="container mw-layout-container safe-mode no-element   edit" field="layout-content-skin-31-{{ $params['id'] }}" rel="module">
        <div class="row text-center mb-5 ">
            <div class="regular-mode">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-3">Your Awesome Title Here</h3>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">Audio player software is used to play back sound recordings in one of the many formats available for computers today</p>
                <br/>
                <module type="btn" button_style="btn-primary" text="Learn More"/>
            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
