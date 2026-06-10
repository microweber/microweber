{{--
type: layout

name: Videos 2

position: 2

categories: Videos
--}}
    @php
        $classes['padding_top'] = $classes['padding_top'] ?? '';
        $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
        $layout_classes = $layout_classes ?? '';
        $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
    @endphp

    <section class="section {{ $layout_classes }}">
        <module type="background" id="background-layout--{{ $params['id'] }}" />
        <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
        <div class="mw-layout-container no-element container safe-mode edit" field="layout-video-skin-2-{{ $params['id'] }}" rel="module">
            <div class="row text-center">
                <div class="col-12 col-lg-10 mx-auto regular-mode">
                    <h1 data-mwplaceholder="{{ __('Enter title here') }}">Share story with a video.</h1>
                    <p data-mwplaceholder="{{ __('Enter text here') }}">
                        Authenticity is key. <br> Share your journey, your passion, and the real reason behind your venture. <br>
                        People connect with genuine stories.
                    </p>
                    <module class="safe-mode" type="video" template="default" url="{{ asset('templates/big/videos/example.mp4') }}" height="500"/>
                </div>
            </div>
        </div>
        <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
    </section>

