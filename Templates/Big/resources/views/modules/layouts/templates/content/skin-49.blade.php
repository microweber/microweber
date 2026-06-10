@php
/*

type: layout

name: Content 49

position: 49

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
    <div class="container mw-layout-container safe-mode no-element    edit" field="layout-content-skin-49-{{ $params['id'] }}" rel="module">
        <div class="row text-center nodrop no-select">
            <div class="mx-auto col mb-6 cloneable element background-color-element safe-mode mw-scale-hover-effect  allow-select">
                <div class="mh-250 h-100 border p-5  regular-mode d-flex flex-column align-items-center justify-content-center allow-drop">
                    <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="m-0">Your Awesome Title Here</h6>
                </div>
            </div>

            <div class="mx-auto col mb-6 cloneable element background-color-element safe-mode mw-scale-hover-effect  allow-select">
                <div class="mh-250 h-100 border p-5  regular-mode d-flex flex-column align-items-center justify-content-center allow-drop">
                    <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="m-0">Your Awesome Title Here</h6>
                </div>
            </div>

            <div class="mx-auto col mb-6 cloneable element background-color-element safe-mode mw-scale-hover-effect  allow-select">
                <div class="mh-250 h-100 border p-5  regular-mode d-flex flex-column align-items-center justify-content-center allow-drop">
                    <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="m-0">Your Awesome Title Here</h6>
                </div>
            </div>

            <div class="mx-auto col mb-6 cloneable element background-color-element safe-mode mw-scale-hover-effect  allow-select">
                <div class="mh-250 h-100 border p-5  regular-mode d-flex flex-column align-items-center justify-content-center allow-drop">
                    <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="m-0">Your Awesome Title Here</h6>
                </div>
            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
