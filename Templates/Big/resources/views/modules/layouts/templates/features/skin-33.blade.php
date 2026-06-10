{{--
 type: layout
 name: Feature 33
 position: 33
 categories: Features
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? ''; 
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-feature-skin-33-{{ $params['id'] }}" rel="module">
        <div class="row m-1 text-center text-sm-start">
            <div class="mx-auto col-sm-10 col-md-6 col-lg-6 mb-4 px-1 cloneable element background-color-element safe-mode">
                <div class="h-100 d-flex flex-column border border-color-primary p-5 mx-3 regular-mode">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Internet Advertising What Went Wrong</h3>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-2">There is a moment in the life of any aspiring astronomer that it is time to buy that first telescope. It’s exciting to think about setting up your own</p>
                    <br />
                    <module type="btn" button_text="Explore" button_style="btn-primary"/>
                </div>
            </div>

            <div class="mx-auto col-sm-10 col-md-6 col-lg-6 mb-4 px-1 cloneable element background-color-element safe-mode">
                <div class="h-100 d-flex flex-column border border-color-primary p-5 mx-3 regular-mode">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Internet Advertising What Went Wrong</h3>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-2">There is a moment in the life of any aspiring astronomer that it is time to buy that first telescope. It’s exciting to think about setting up your own</p>
                    <br />
                    <module type="btn" button_text="Explore" button_style="btn-primary"/>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
