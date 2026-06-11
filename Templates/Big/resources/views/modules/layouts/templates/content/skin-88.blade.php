{{--
type: layout

    name: Content 88

    position: 88

    categories: Content
--}}

<style>
    .content-88-box {
        background-color: rgb(var(--mw-primary-color) / .6);
        flex: 1 0 auto;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
    }
</style>

<x-layout-section
    :has-background="false"
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-layout-dark-background py-0 d-flex align-items-top gap-3 align-items-center justify-content-center"
    default-padding-top="pt-10"
    default-padding-bottom="pb-10"
    container-class="mw-layout-container no-element"
>
    <module type="background" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" id="background-layout--{{ $params['id'] }}" />
    <div class="container mw-layout-container safe-mode mw-layout-overlay-container {{ $layout_classes ?? '' }} pt-10 pb-10 edit" field="layout-content-skin-88-{{ $params['id'] }}" rel="module">
            <x-row>
                <div class="content-88-box p-md-5 p-3 col-md-6 col-12 cloneable background-color-element element" style="background-color: rgb(var(--mw-primary-color) / .6);">
                    <div class="d-flex align-items-top gap-3">
                        <div>
                            <h3>01</h3>
                        </div>
                        <div>
                            <h3>Web Design</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat. Aenean eu felis nisi.</p>
                        </div>
                    </div>
                </div>

                <div class="content-88-box p-md-5 p-3 background-color-element col-md-6 col-12 cloneable element">
                    <div class="d-flex align-items-top gap-3">
                        <div>
                            <h3>02</h3>
                        </div>
                        <div>
                            <h3>Product Design</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat. Aenean eu felis nisi.</p>
                        </div>
                    </div>
                </div>

                <div class="content-88-box p-md-5 p-3 background-color-element col-md-6 col-12 cloneable element">
                    <div class="d-flex align-items-top gap-3">
                        <div>
                            <h3>03</h3>
                        </div>
                        <div>
                            <h3>Branding</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat. Aenean eu felis nisi.</p>
                        </div>
                    </div>
                </div>

                <div class="content-88-box p-md-5 p-3 background-color-element col-md-6 col-12 cloneable element" style="background-color: rgb(var(--mw-primary-color) / .6);">
                    <div class="d-flex align-items-top gap-3">
                        <div>
                            <h3>04</h3>
                        </div>
                        <div>
                            <h3>Graphic and video</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi scelerisque justo sed mattis volutpat. Aenean eu felis nisi.</p>
                        </div>
                    </div>
                </div>
            </x-row>
        </div>
</x-layout-section>
