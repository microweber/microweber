@php
/*

type: layout

name: Content 37

position: 37

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
    <div class="container mw-layout-container safe-mode no-element   edit" field="layout-content-skin-37-{{ $params['id'] }}" rel="module">
        <div class="row text-center mb-3 ">
            <div class="col-12 col-lg-8 col-lg-8 mx-auto  allow-drop allow-select">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-2">Your Title Here</h3>
            </div>
        </div>

        <div></div>

        <div class="row cloneable mb-3 py-md-3 nodrop no-select">
            <div class="mx-auto col-sm-10 col-md-6 col-lg-4 mb-6 cloneable element background-color-element safe-mode  allow-select">
                <div class="h-100 d-flex flex-column border  h-100 px-5 py-5 no-drag">
                    <div class=" regular-mode  text-center allow-drop no-drag" style="min-height: 100px;">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Las Vegas</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-3">For business professionals caught between high OEM price and mediocre print and graphic output.</p>
                        <module type="btn" button_style="btn-primary" text="Learn more"/>
                    </div>
                </div>
            </div>

            <div class="mx-auto col-sm-10 col-md-6 col-lg-4 mb-6 cloneable element background-color-element safe-mode  allow-select">
                <div class="h-100 d-flex flex-column border  h-100 px-5 py-5 no-drag">
                    <div class=" regular-mode  text-center allow-drop no-drag" style="min-height: 100px;">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Computer Hardware</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-3">Your business, and pay a lot less for an external IT professional to help you when you need it.</p>
                        <module type="btn" button_style="btn-primary" text="Learn more"/>
                    </div>
                </div>
            </div>

            <div class="mx-auto col-sm-10 col-md-6 col-lg-4 mb-6 cloneable element background-color-element safe-mode  allow-select">
                <div class="h-100 d-flex flex-column border h-100  px-5 py-5 no-drag">
                    <div class=" regular-mode  text-center allow-drop no-drag" style="min-height: 100px;">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Choosing The Best</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-3">According to the research firm Frost & Sullivan, the estimated size of the North American used test</p>
                        <module type="btn" button_style="btn-primary" text="Learn more"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
