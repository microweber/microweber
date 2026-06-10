{{--
type: layout

name: Videos 4

position: 4

categories: Videos
--}}
    @php
        $classes['padding_top'] = $classes['padding_top'] ?? '';
        $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
        $layout_classes = $layout_classes ?? '';
        $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
    @endphp

    <section class="section {{ $layout_classes }}">
        <module type="background" data-background-color="#ddd" id="background-layout--{{ $params['id'] }}" />
        <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
        <div class="mw-layout-container d-flex align-items-center justify-content-center no-element container edit" field="layout-video-skin-4-{{ $params['id'] }}" rel="module" style="min-height: 80vh;">
            <div class="row text-center">
                <div class="col-12 col-md-10 mx-auto regular-mode">
                    <h1 data-mwplaceholder="{{ __('Enter title here') }}">Share story with a video.</h1>
                    <p data-mwplaceholder="{{ __('Enter text here') }}">Authenticity is key. Share your journey, your passion, and the real reason behind your venture. People connect with genuine stories.</p>
                    <module class="safe-mode" type="video" url="{{ asset('templates/big/videos/example.mp4') }}"/>
                </div>
            </div>
        </div>
        <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
    </section>

