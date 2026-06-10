@php
/*

type: layout

name: Content 34

position: 34

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
    <div class="container mw-layout-container safe-mode no-element   edit " field="layout-content-skin-34-{{ $params['id'] }}" rel="module">
        <div class="row text-center mb-5 ">
            <div class="col-12 col-lg-8 col-lg-8 mx-auto   safe-mode allow-drop allow-select">

                 <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-7">Free Real Estate Listing</h5>

                <div class=" d-flex justify-content-around nodrop">
                    <i class="icon-size-72px mb-7 safe-element cloneable element mw-micon-Home"></i>
                    <i class="icon-size-72px mb-7 safe-element cloneable element mw-micon-Building"></i>
                    <i class="icon-size-72px mb-7 safe-element cloneable element mw-micon-Compass-4"></i>
                </div>
                <div class="regular-mode">
                    <p data-mwplaceholder="{{ _e('Enter title here') }}">What if you "think" you don't know enough about your topic? Then, do some research. Read books and magazines. Do some searches on the Internet. Who knows? You might find an area, a niche, that is just waiting for you to fill it with useful information.</p>
                </div>
            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
