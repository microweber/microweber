{{--
type: layout

    name: Content 86 - Parallax

    position: 86

    categories: Content
--}}

<style>
    .text-info {
        color: var(--mw-primary-color);
    }
</style>

<x-layout-section
    :has-background="false"
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-parallax mw-layout-dark-background d-flex align-items-end justify-content-center"
    container-class="mw-layout-container no-element"
>
    <module type="background" data-parallax="true" data-overlay-x="1" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" id="background-layout--{{ $params['id'] }}" />
        <module type="spacer" height="110px" id="spacer-layout--{{ $params['id'] }}-top" />

        <div class="mw-layout-container safe-mode no-element">
            <div class="container mw-layout-overlay-container {{ $layout_classes ?? '' }} text-white edit" field="layout-content-skin-86-{{ $params['id'] }}" rel="module">
                <x-row class="align-items-center py-5">
                    <div class="col-lg-9 col-12">
                        <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="text-white mb-3">Become an <u class="text-info">event speaker?</u></h2>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}"> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut dolore</p>
                    </div>
                    <div class="col-lg-3 col-12 ms-lg-auto mt-4 mt-lg-0">
                        <module type="btn" button_style="btn-primary" text="Register Today"/>
                    </div>
                </x-row>
            </div>
        </div>
        <module type="spacer" height="110px" id="spacer-layout--{{ $params['id'] }}-bottom" />
</x-layout-section>
