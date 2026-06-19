{{--
type: layout

name: Videos 5

position: 5

categories: Videos
--}}

<style>
        .spacer-layout-field---{{ $params['id'] }}-top,
        .spacer-layout-field---{{ $params['id'] }}-bottom {
            height: 120px;
        }
    </style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-video-skin-5"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row class="text-center regular-mode">
                    <h1 data-mwplaceholder="{{ __('Enter title here') }}">Share story with a video.</h1>
                    <p data-mwplaceholder="{{ __('Enter text here') }}">People connect with genuine stories.</p>

                    <div class="col-md-6 mx-auto cloneable element">
                        <module class="safe-mode" type="video" template="default" url="{{ asset('templates/big/videos/example.mp4') }}"/>
                    </div>
                    <div class="col-md-6 mx-auto cloneable element">
                        <module class="safe-mode" type="video" template="default" url="{{ asset('templates/big/videos/example.mp4') }}"/>
                    </div>
                </x-row>
</x-layout-section>
