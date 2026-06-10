@php
/*

type: layout

name: Content 4

position: 4

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
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="container mw-layout-container safe-mode no-element   edit " field="layout-content-skin-4-{{ $params['id'] }}" rel="module">
        <div class="row text-center mb-3 nodrop no-select">
            <div class="col-12 col-lg-10 col-lg-8 mx-auto ">
                <div class="regular-mode allow-select allow-drop">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}">A Great Title For This Section</h3>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-3">Remember, your story is a dynamic tool that can evolve and adapt as your venture progresses. The way you tell your story online can indeed make a significant difference in building connections, generating interest, and achieving your goals.</p>
                </div>
            </div>
        </div>

        <div class="row mb-3 py-4  nodrop no-select">
            <div class="col-12 col-md-6 col-lg-3 mb-md-4 p-4 d-flex flex-column cloneable element background-color-element allow-select allow-drop">

                    <h4 data-mwplaceholder="{{ _e('Enter title here') }}">First Title</h4>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">Point of Sale hardware, the till at a shop check out, has become very complex</p>
                    <module type="btn" button_style="btn-primary" text="Learn more"/>

            </div>

            <div class="col-12 col-md-6 col-lg-3 mb-md-4 p-4 d-flex flex-column cloneable element background-color-element allow-select allow-drop"">

                    <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Second Title</h4>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">Point of Sale hardware, the till at a shop check out, has become very complex</p>

                    <module type="btn" button_style="btn-primary" text="Learn more"/>

            </div>

            <div class="col-12 col-md-6 col-lg-3 mb-md-4 p-4 d-flex flex-column cloneable element background-color-element allow-select allow-drop"">

                    <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Third Title</h4>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">Point of Sale hardware, the till at a shop check out, has become very</p>

                    <module type="btn" button_style="btn-primary" text="Learn more"/>

            </div>

            <div class="col-12 col-md-6 col-lg-3 mb-md-4 p-4 d-flex flex-column cloneable element background-color-element allow-select allow-drop"">

                    <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Last Title</h4>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">Point of Sale hardware, the till at a shop check out, has become very complex</p>

                    <module type="btn" button_style="btn-primary" text="Learn more"/>

            </div>
        </div>

    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>

</section>
