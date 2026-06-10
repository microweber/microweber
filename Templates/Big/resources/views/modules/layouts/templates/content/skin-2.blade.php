@php
/*

type: layout

name: Content 2

position: 2

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


<section class="section {{ $layout_classes }}  no-drop">

    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />

    <div class="container mw-layout-container safe-mode no-element   text-center edit safe-mode" field="layout-content-skin-2-{{ $params['id'] }}" rel="module">
      <div class="row nodrop no-select">
          <div class="col-12 col-lg-10 col-lg-8 mx-auto  allow-select allow-drop" style="min-height: 40px">
              <div class="mb-4 no-element">
                  <i class="safe-element no-typing mw-micon-Anchor mb-4 icon-size-64px"></i>
              </div>
              <div class="regular-mode">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Your Story Should Evolve Over Time</h3>
                <p data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-3">Update your audience on new developments and how
                    <br>
                    you're overcoming challenges.
                </p>
              </div>
          </div>
      </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
