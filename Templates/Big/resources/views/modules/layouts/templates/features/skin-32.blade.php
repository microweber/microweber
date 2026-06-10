{{--
 type: layout
 name: Feature 32
 position: 32
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
    <div class="mw-layout-container no-element container edit safe-mode" field="layout-feature-skin-32-{{ $params['id'] }}" rel="module">
        <div class="row m-1 text-center text-sm-center">
            <div class="mx-auto col-sm-10 col-md-6 col-lg-4 mb-2 px-1 cloneable element background-color-element safe-mode">
                <div class="h-100 d-flex flex-column border p-5 mx-3">
                    <div class="d-block d-sm-flex align-items-center h-100">
                        <div class="regular-mode">
                            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Make Money Online Through Advertising</h5>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-2">If you are a serious astronomy fanatic like a lot of us are, you can probably remember that one event</p>
                            <br />
                            <module type="btn" text="Learn More" button_style="btn-primary" button_size=""/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mx-auto col-sm-10 col-md-6 col-lg-4 mb-2 px-1 cloneable element background-color-element safe-mode">
                <div class="h-100 d-flex flex-column border p-5 mx-3">
                    <div class="d-block d-sm-flex align-items-center h-100">
                        <div class="regular-mode">
                            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Adwords Keyword Research For Beginners</h5>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-2">If you are a serious astronomy fanatic like a lot of us are, you can probably remember that one event</p>
                            <br />
                            <module type="btn" text="Learn More" button_style="btn-primary" button_size=""/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
