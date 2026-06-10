@php
/*

type: layout

name: Content 57

position: 57

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
    <div class="container mw-layout-container safe-mode no-element   d-flex justify-content-center align-items-center edit safe-mode " field="layout-content-skin-57-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-12 col-lg-12 col-lg-12 mx-auto">
                <div class="row">
                    <div class="col-md-4 mb-6   cloneable element background-color-element regular-mode allow-drop allow-select">
                        <h6 data-mwplaceholder="{{ _e('Enter title here') }}">Your Awesome Title Here</h6>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Rack mount LCD monitors can save you a lot of space and help you form a convenient and efficient desktop for your work or home study. </p>
                    </div>

                    <div class="col-md-4 mb-6   cloneable element background-color-element regular-mode allow-drop allow-select">
                        <h6 data-mwplaceholder="{{ _e('Enter title here') }}">Your Awesome Title Here</h6>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Rack mount LCD monitors can save you a lot of space and help you form a convenient and efficient desktop for your work or home study. </p>
                    </div>

                    <div class="col-md-4 mb-6   cloneable element background-color-element regular-mode allow-drop allow-select">
                        <h6 data-mwplaceholder="{{ _e('Enter title here') }}">Your Awesome Title Here</h6>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Shure's Music Phone Adapter (MPA) is our favorite iPhone solution, since it lets you use the headphones you're most comfortable with. It has an iPhone-compatible jack</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
