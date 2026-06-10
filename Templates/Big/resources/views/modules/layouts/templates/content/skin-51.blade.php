@php
/*

type: layout

name: Content 51

position: 51

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
    <div class="container mw-layout-container safe-mode no-element   edit" field="layout-content-skin-51-{{ $params['id'] }}" rel="module">
        <div class="row nodrop no-select">
            <div class="col-md-6 element cloneable background-color-element mw-scale-hover-effect   allow-select">
                <div class="border p-5 d-flex align-items-center no-select ">
                    <div class="regular-mode allow-drop allow-select ">
                        <h5 data-mwplaceholder="{{ _e('Enter title here') }}" >Space The Final Frontier</h5>
                        <p  data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-0">Shure's Music Phone Adapter (MPA) is our favorite</p>
                    </div>

                    <i class="mw-micon-Arrow-Right icon-size-36px safe-element allow-select no-drag ms-auto"></i>
                </div>

            </div>

            <div class="col-md-6 element cloneable background-color-element mw-scale-hover-effect  allow-select">
               <div class="border p-5 d-flex align-items-center no-select ">
                   <div class="regular-mode allow-drop allow-select">
                       <h5 data-mwplaceholder="{{ _e('Enter title here') }}" >Beyond The Naked Eye</h5>
                       <p  data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-0">Shure's Music Phone Adapter (MPA) is our favorite </p>
                   </div>

                   <i class="mw-micon-Arrow-Right icon-size-36px safe-element allow-select no-drag ms-auto"></i>
               </div>
            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
