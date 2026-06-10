@php
/*

type: layout

name: Content 6

position: 6

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
    .object-fit-cover img {
        object-fit: cover !important;
    }
</style>

<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="container mw-layout-container safe-mode no-element   edit " field="layout-content-skin-6-{{ $params['id'] }}" rel="module">
        <div class="row text-center">
            <div class="col-12 col-lg-10 col-lg-8 mx-auto  allow-select">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}">The Amazing Team</h3>
            </div>
        </div>

        <div class="row text-center mt-7 nodrop">
            <div class="mx-auto col-6 col-sm-4 mb-5 cloneable element background-color-element safe-mode allow-select">
                <div class="p-2 border ">
                    <div class="img-as-background object-fit-cover rounded-circle square  allow-select allow-select">
                        <img loading="lazy" class="h-100 w-100" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt=""/>
                    </div>

                    <div class="text-center mt-6 regular-mode  allow-select  allow-drop ">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Some Title Here</h4>
                        <module type="btn" button_style="btn-primary" button_size="btn-md" text="Learn more"/>
                    </div>
                </div>
            </div>

            <div class="mx-auto col-6 col-sm-4 mb-5 cloneable element background-color-element safe-mode allow-select">
                <div class="p-2 border ">
                    <div class="img-as-background object-fit-cover rounded-circle square  allow-select ">
                        <img loading="lazy" class="h-100 w-100" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" alt=""/>
                    </div>

                    <div class="text-center mt-6  regular-mode  allow-select allow-drop  ">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Some Title Here</h4>
                        <module type="btn" button_style="btn-primary" button_size="btn-md" text="Learn more"/>
                    </div>
                </div>
            </div>

            <div class="mx-auto col-6 col-sm-4 mb-5 cloneable element background-color-element safe-mode   allow-select">
                <div class="p-2 border ">
                    <div class="img-as-background object-fit-cover rounded-circle square  allow-select ">
                        <img loading="lazy" class="h-100 w-100" src="{{ asset('templates/big/img/layouts/gallery-1-3.jpg') }}" alt=""/>
                    </div>

                    <div class="text-center mt-6  regular-mode  allow-select  allow-drop ">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Some Title Here</h4>
                        <module type="btn" button_style="btn-primary" button_size="btn-md" text="Learn more"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
