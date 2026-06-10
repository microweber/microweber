@php
/*

type: layout

name: Content 54

position: 54

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
    <div class="container mw-layout-container safe-mode no-element   edit" field="layout-content-skin-54-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 col-lg-10 col-lg-6 mx-auto text-center allow-drop allow-select ">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Your Title Here</h3>
                <p data-mwplaceholder="{{ _e('Enter title here') }}">Ah, the technical interview. Nothing like it. Not only does it cause anxiety,
                    <br> but it causes anxiety for several different reasons.</p>
                <br/>

                <div class="d-flex align-items-center justify-content-center ">
                    <div class="cloneable element mx-2">
                        <module type="btn" button_style="btn-primary"  text="Buy"/>
                    </div>
                    <div class="cloneable element mx-2">
                        <module type="btn" button_style="btn-link" text="Learn More"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
