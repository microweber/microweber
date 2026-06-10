{{--
type: layout

name: Videos 5

position: 5

categories: Videos
--}}
    @php
        $classes['padding_top'] = $classes['padding_top'] ?? '';
        $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
        $layout_classes = $layout_classes ?? '';
        $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
    @endphp

    <style>
        .spacer-layout-field---{{ $params['id'] }}-top,
        .spacer-layout-field---{{ $params['id'] }}-bottom {
            height: 120px;
        }
    </style>

    <section class="section {{ $layout_classes }}">
        <module type="background" id="background-layout--{{ $params['id'] }}" />
        <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" height="120px" />
        <div class="mw-layout-container no-element container edit safe-mode" field="layout-video-skin-5-{{ $params['id'] }}" rel="module">
            <div class="row text-center regular-mode">
                <h1 data-mwplaceholder="{{ __('Enter title here') }}">Share story with a video.</h1>
                <p data-mwplaceholder="{{ __('Enter text here') }}">People connect with genuine stories.</p>

                <div class="col-md-6 mx-auto cloneable element">
                    <module class="safe-mode" type="video" template="default" url="{{ asset('templates/big/videos/example.mp4') }}"/>
                </div>
                <div class="col-md-6 mx-auto cloneable element">
                    <module class="safe-mode" type="video" template="default" url="{{ asset('templates/big/videos/example.mp4') }}"/>
                </div>
            </div>
        </div>
        <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" height="120px" />
    </section>

