{{--
type: layout

name: Videos 6

position: 6

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
        <div class="mw-layout-container no-element container safe-mode edit" field="layout-video-skin-6-{{ $params['id'] }}" rel="module">
            <div class="row text-center">
                <div class="col-md-6 text-left pt-4 mx-auto element regular-mode">
                    <h1 data-mwplaceholder="{{ __('Enter title here') }}">Share it.</h1>
                    <p data-mwplaceholder="{{ __('Enter text here') }}">
                        Clearly define your idea, business, hobby, or project. What is its purpose? What problem does it solve? Having a clear vision will help you articulate your story more effectively.
                        <br><br><br>
                        Understand your target audience. Who are they? What are their interests, needs, and pain points? Tailor your story to resonate with them.
                    </p>
                </div>
                <div class="col-md-6 mx-auto">
                    <module class="safe-mode" type="video" template="default" url="{{ asset('templates/big/videos/example.mp4') }}" />
                </div>
            </div>
        </div>
        <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
    </section>

