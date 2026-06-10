@php
    /*

    type: layout

    name: Content 85

    position: 85

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
    .avatar-image {
        border: 2px solid #fff;
        border-radius: 100px;
        width: 50px;
        height: 50px;
        object-fit: cover;
    }

    .avatar-image-left {
        position: relative;
        left: -10px;
    }

    .avatar-image-left + .avatar-image-left {
        left: -20px;
    }

    .avatar-image-left + .avatar-image-left + .avatar-image-left {
        left: -30px;
    }

    .avatar-image-left + .avatar-image-left + .avatar-image-left + .avatar-image-left {
        left: -40px;
    }

    .avatar-info {
        display: inline-block;
        vertical-align: top;
    }
</style>

<section class="section mw-content-85-about {{ $layout_classes }} section-content-83 pb-0">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] }}-top" />

    <div class="mw-layout-container safe-mode no-element edit container  safe-mode" field="layout-content-skin-85-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-lg-10 col-12 allow-select safe-mode">
                <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Our <u class="text-info">Story</u></h2>
            </div>

            <div class="col-lg-6 col-12 allow-select safe-mode">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-3">The importance of Leadership Conference in 2022</h3>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">Leadership Event is one-page Bootstrap v5.1.3 CSS layout for your website. Thank you for choosing TemplateMo website where you can instantly download free CSS templates at no cost.</p>
                <div class="d-flex gap-3 mt-3">
                    <module type="btn" text="Lean more" button_style="btn-outline-primary"/>
                    <module type="btn" text="Lean more" button_style="btn-primary"/>
                </div>
            </div>

            <div class="col-lg-6 col-12 mt-5 mt-lg-0 allow-select safe-mode">
                <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut dolore</h4>
                <div class="avatar-group border-top py-5 mt-5">
                    <img class="img-fluid avatar-image cloneable element p-0" loading="lazy" src="{{ asset('modules/teamcard/default-content/1.jpg') }}" alt=""/>
                    <img class="img-fluid avatar-image avatar-image-left cloneable element p-0" loading="lazy" src="{{ asset('modules/teamcard/default-content/2.jpg') }}" alt=""/>
                    <img class="img-fluid avatar-image avatar-image-left cloneable element p-0" loading="lazy" src="{{ asset('modules/teamcard/default-content/3.jpg') }}" alt=""/>
                    <img class="img-fluid avatar-image avatar-image-left cloneable element p-0" loading="lazy" src="{{ asset('modules/teamcard/default-content/4.jpg') }}" alt=""/>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}" class="d-inline">120+ People are attending with us</p>
                </div>
            </div>
        </div>
    </div>

    <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
