@php
/*

type: layout

name: Content 48

position: 48

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
    <div class="container mw-layout-container safe-mode no-element   edit" field="layout-content-skin-48-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 col-sm-10 col-lg-10 mx-auto">
                <div class="border-bottom py-3 cloneable element background-color-element safe-mode allow-select">
                    <div class="row ">
                        <div class="col text-center text-md-start    regular-mode allow-select allow-drop">
                            <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Your Title Here</h4>

                            <p data-mwplaceholder="{{ _e('Enter text here') }}">In the history of modern astronomy, there is probably no one greater leap forward than the building and launch of the space telescope known as the Hubble.</p>
                        </div>

                        <div class="col-12 col-md-auto text-center ps-5 align-self-center cloneable allow-select allow-drop">
                            <module type="btn" text="Learn More" button_style="btn-primary"/>
                        </div>
                    </div>
                </div>

                <div class="border-bottom py-3 cloneable element background-color-element safe-mode allow-select">
                    <div class="row ">
                        <div class="col text-center text-md-start    regular-mode allow-select allow-drop">
                            <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Your Title Here</h4>

                            <p data-mwplaceholder="{{ _e('Enter text here') }}">In the history of modern astronomy, there is probably no one greater leap forward than the building and launch of the space telescope known as the Hubble.</p>
                        </div>

                        <div class="col-12 col-md-auto text-center ps-5 align-self-center cloneable allow-select allow-drop">
                            <module type="btn" text="Learn More" button_style="btn-primary"/>
                        </div>
                    </div>
                </div>

                <div class="border-bottom py-3 cloneable element background-color-element safe-mode allow-select">
                    <div class="row ">
                        <div class="col text-center text-md-start    regular-mode allow-select allow-drop">
                            <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Your Title Here</h4>

                            <p data-mwplaceholder="{{ _e('Enter text here') }}">In the history of modern astronomy, there is probably no one greater leap forward than the building and launch of the space telescope known as the Hubble.</p>
                        </div>

                        <div class="col-12 col-md-auto text-center ps-5 align-self-center cloneable allow-select allow-drop">
                            <module type="btn" text="Learn More" button_style="btn-primary"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
