{{--
type: layout
name: Header 25 - Parallax
position: 25
categories: Header
--}}

<style>
    .mw-header-25 {
        position: absolute;
        bottom: 100px;
        left: 50px;
    }

    @media (max-width: 600px) {
        .mw-header-25 {
            left: 10px;
            bottom: 150px;
        }
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section py-0 mw-layout-parallax guesthouse-header d-flex align-items-center justify-content-center"
    :has-spacers="false"
    default-padding-top="mw-p-t-10"
    default-padding-bottom="mw-p-b-100"
    container-class="mw-layout-container no-element"
>
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/sections/main-home.jpg') }}" class="mw-header-section-mh-100vh" style="min-height: calc(100vh - 70px)"/>
        <div class="container mw-layout-container mw-header-25  edit" field="layout-header-skin-24-{{ $params['id'] ?? '' }}" rel="module">
            <x-row>
                <div class="col-12 safe-mode">
                    <div class="allow-select info-holder">
                        <h2 data-mwplaceholder="@lang('Enter title here')" class="fx-deactivate">Welcome to our <strong>Cliff House</strong></h2>
                        <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p">The heart of the mountain</p>
                    </div>
                </div>
            </x-row>
        </div>
</x-layout-section>
