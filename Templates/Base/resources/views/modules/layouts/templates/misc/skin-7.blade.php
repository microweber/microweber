{{--
type: layout

name: Misc 7

position: 7

categories: Misc
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section edit safe-mode"
    field-name="layout-misc-skin-7"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row>
                <div class="col-12 d-flex flex-wrap">
                    <div class="col-sm-4 cloneable element safe-mode">
                        <h5>{{ _lang("Space The Final Frontier") }}</h5>
                        <p>{{ _lang("But for many of us, it was that first time we saw a rain of fire from.") }}</p>
                    </div>
                    <div class="col-sm-8 row align-self-center text-center">
                        <div class="col-6 col-lg-3 mb-5 cloneable element safe-mode">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/Facebook2.png') }}" alt="">
                        </div>
                        <div class="col-6 col-lg-3 mb-5 cloneable element safe-mode">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/Google2.png') }}" alt="">
                        </div>
                        <div class="col-6 col-lg-3 mb-5 cloneable element safe-mode">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/Logitech.png') }}" alt="">
                        </div>
                        <div class="col-6 col-lg-3 mb-5 cloneable element safe-mode pe-0">
                            <img loading="lazy" src="{{ asset('templates/big/img/layouts/Philips.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </x-row>
</x-layout-section>
