{{--
type: layout

name: Videos 9

position: 9

categories: Videos
--}}
    @php
        $classes['padding_top'] = $classes['padding_top'] ?? '';
        $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
        $layout_classes = $layout_classes ?? '';
        $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
    @endphp

    <section class="section video-skin-9 {{ $layout_classes }}">
        <module type="background" id="background-layout--{{ $params['id'] }}" />
        <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
        <div class="mw-layout-container no-element container-fluid safe-mode edit" field="layout-video-skin-9-{{ $params['id'] }}" rel="module">
            <div class="row justify-content-center align-items-center text-center safe-mode">
                <div class="col-lg-5 mx-auto position-relative">
                    <module class="safe-mode" type="video" template="default" url="{{ asset('templates/big/videos/example.mp4') }}" height="500"/>
                </div>
                <div class="col-lg-5 text-left pt-4 mx-auto position-relative">
                    <div class="position-relative regular-mode" style="z-index: 2;">
                        <h2 data-mwplaceholder="{{ __('Enter title here') }}" class="mb-4"/>Share your story with a video</h2>
                        <p data-mwplaceholder="{{ __('Enter text here') }}" class="mt-1">
                            Authenticity is key. Share your journey, your passion, and the real reason behind your venture. People connect with genuine stories.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center align-items-center text-center safe-mode">
                <div class="col-lg-5 text-left pt-4 mx-auto position-relative regular-mode">
                    <div class="position-relative" style="z-index: 2;">
                        <h2 class="mb-4"/>Share your story with a video</h2>
                        <p data-mwplaceholder="{{ __('Enter text here') }}" class="mt-1">
                            Authenticity is key. Share your journey, your passion, and the real reason behind your venture. People connect with genuine stories.
                        </p>
                    </div>
                </div>
                <div class="col-lg-5 mx-auto position-relative">
                    <module class="safe-mode" type="video" template="default" url="{{ asset('templates/big/videos/example.mp4') }}" height="500"/>
                </div>
            </div>
        </div>
        <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
    </section>
