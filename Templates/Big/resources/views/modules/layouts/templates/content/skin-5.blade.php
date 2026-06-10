@php
/*

type: layout

name: Content 5

position: 5

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

<style>
     #section-{{ $params['id'] }}{
        --gap: 15px;
     }
     #section-{{ $params['id'] }} .content-skin-5-image{
        height: 300px;
        width: 100%;
        object-fit: cover;

     }
     .content-skin-5-row {
        display: flex;
        flex-wrap: wrap;
        align-items: start;
        justify-content: start;
        gap: var(--gap);
     }
     .content-skin-5-col{
        width: calc(33.333% - var(--gap));
        padding: 0;
     }

     @media (max-width: 980px) {
        .content-skin-5-col{
            width: calc(50% - var(--gap));
        }
     }
      @media (max-width:768px) {
        .content-skin-5-col{
            width: 100%;
            max-width: 450px;
            margin-inline: auto;
        }
     }
</style>

<section class="section {{ $layout_classes }}" id="section-{{ $params['id'] }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="container mw-layout-container safe-mode no-element   safe-mode edit " field="layout-content-skin-5-{{ $params['id'] }}" rel="module">
        <div class="content-skin-5-row mb-3 py-4">
            <div class="content-skin-5-col  cloneable element background-color-element safe-mode allow-select p-2 border allow-drop">


                    <img loading="lazy" class="content-skin-5-image" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt=""/>

                    <div class="regular-mode px-5 pt-3 pb-3 mt-md-auto mt-5 allow-select">
                        <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-2">Shooting Stars</h6>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">While it was just a TV show, that little speech at the beginning</p>
                    </div>

            </div>

            <div class="content-skin-5-col  cloneable element background-color-element safe-mode allow-select p-2 border allow-drop">


                      <img loading="lazy" class="content-skin-5-image"  src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt=""/>

                   <div class="  safe-mode px-5 pt-3 pb-3 mt-md-auto mt-5 allow-select">
                       <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-2">Moon Gazing</h6>
                       <p data-mwplaceholder="{{ _e('Enter text here') }}">While it was just a TV show, that little speech at the beginning</p>
                   </div>

            </div>

            <div class="content-skin-5-col  cloneable element background-color-element safe-mode allow-select p-2 border allow-drop">


                      <img loading="lazy" class="content-skin-5-image"  src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt=""/>

                   <div class="regular-mode px-5 pt-3 pb-3 mt-md-auto mt-5 allow-select">
                       <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-2">Astronomy Or Astrology</h6>
                       <p data-mwplaceholder="{{ _e('Enter text here') }}">While it was just a TV show, that little speech at the beginning</p>
                   </div>

            </div>

            <div class="content-skin-5-col  cloneable element background-color-element safe-mode allow-select p-2 border allow-drop">


                    <img loading="lazy" class="content-skin-5-image"  src="{{ asset('templates/big/img/layouts/gallery-1-4.jpg') }}" alt=""/>

                   <div class="regular-mode px-5 pt-3 pb-3 mt-md-auto mt-5 allow-select">
                       <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-2">Moon Fever</h6>
                       <p data-mwplaceholder="{{ _e('Enter text here') }}">While it was just a TV show, that little speech at the beginning</p>
                   </div>

            </div>

            <div class="content-skin-5-col  cloneable element background-color-element safe-mode allow-select p-2 border allow-drop">


                       <img loading="lazy" class="content-skin-5-image"  src="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" alt=""/>

                   <div class="regular-mode px-5 pt-3 pb-3 mt-md-auto mt-5 allow-select">
                       <h6 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-2">The Night Sky</h6>
                       <p data-mwplaceholder="{{ _e('Enter text here') }}">While it was just a TV show, that little speech at the beginning</p>
                   </div>

            </div>
        </div>

        <div class="content-skin-5-col-full  safe-mode text-center allow-select">
            <module type="btn" button_style="btn-primary" text="See More"/>
        </div>

    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
