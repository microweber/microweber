{{--
type: layout

name: Videos 8

position: 8

categories: Videos
--}}

<style>
        .video-skin-8 iframe {
            border-radius: 30px;
        }
    </style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section video-skin-8"
    container-class="mw-layout-container no-element container-fluid"
>
    <x-row class="justify-content-center align-items-center text-center safe-mode edit" field="layout-video-skin-8-{{ $params['id'] }}" rel="module">
                    <div class="col-lg-5 mx-auto">
                        <module class="safe-mode" type="video" template="default" url="{{ asset('templates/big/videos/example.mp4') }}" height="500"/>
                    </div>
                    <div class="col-lg-5 text-left pt-4 mx-auto safe-mode">
                        <div class="position-relative regular-mode">
                            <h2 data-mwplaceholder="{{ __('Enter title here') }}" class="mb-4"/>Share story with a video.</h2>
                            <p data-mwplaceholder="{{ __('Enter text here') }}" class="mt-1">
                                Clearly define your idea, business, hobby, or project. What is its purpose? What problem does it solve? Having a clear vision will help you articulate your story more effectively.
                            </p>
                        </div>
                    </div>
                </x-row>
</x-layout-section>
