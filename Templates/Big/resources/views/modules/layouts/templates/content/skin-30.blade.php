{{--
type: layout

name: Content 30

position: 30

categories: Content
--}}

<x-layout-section
    :has-background="false"
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-dark-background"
    default-padding-top="pt-10"
    default-padding-bottom="pb-10"
    container-class="mw-layout-container no-element"
>
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" id="background-layout--{{ $params['id'] }}" />

        <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] }}-top" />

        <div class="container mw-layout-container safe-mode   mw-layout-overlay-container {{ $layout_classes ?? '' }} edit" field="layout-content-skin-30-{{ $params['id'] }}" rel="module">
            <x-row class="background-color-element">
                <div class="col-12 col-sm-10 col-lg-8 col-lg-6   ">
                    <div class="regular-mode allow-drop allow-select">
                        <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-3">Make Money Online Through Advertising</h3>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Planning to visit Las Vegas or any other vacational resort where casinos are a major portion of their business? I have just the thing for you</p>
                    </div>
                    <x-row class="mt-8">
                        <div class="col-md-6  regular-mode allow-drop allow-select">
                            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Las Vegas How To Have Non Gambling Related Fun</h5>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">According to the research firm Frost & Sullivan, the estimated size of the North American </p>
                        </div>

                        <div class="col-md-6  regular-mode allow-drop allow-select">
                            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">Stu Unger Rise And Fall Of A Poker Genius</h5>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}">According to the research firm Frost & Sullivan, the estimated size of the North American </p>
                        </div>
                    </x-row>
                </div>
            </x-row>
        </div>

       <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] }}-bottom" />
</x-layout-section>
