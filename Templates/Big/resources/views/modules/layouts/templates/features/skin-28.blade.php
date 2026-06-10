{{--
 type: layout
 name: Feature 28
 position: 28
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
    <div class="mw-layout-container no-element container edit" field="layout-feature-skin-28-{{ $params['id'] }}" rel="module">
        <div class="row text-center">
            <div class="col-12 mx-auto regular-mode">
                <h2 data-mwplaceholder="{{ _e('Enter title here') }}">The Features Title</h2>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">While it was just a TV show, that little speech at the beginning of the original <br> Star Trek show really did do a good job of capturing our feelings about space.</p>
                <br/>
                <module type="video" template="default" url="{{ asset('templates/big/videos/example.mp4') }}" height="700">
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
