{{--
type: layout

name: Misc 8

position: 8

categories: Misc
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-misc-skin-8"
    container-class="mw-layout-container no-element container edit"
>
    <x-row>
                <div class="col cloneable element safe-mode align-self-center">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/Amazon2.png') }}" alt="">
                </div>
                <div class="col cloneable element safe-mode">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/Facebook2.png') }}" alt="">
                </div>
                <div class="col cloneable element safe-mode">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/Google2.png') }}" alt="">
                </div>
                <div class="col cloneable element safe-mode">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/LinkedIn2.png') }}" alt="">
                </div>
                <div class="col cloneable element safe-mode">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/Logitech.png') }}" alt="">
                </div>
                <div class="col cloneable element safe-mode">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/Philips.png') }}" alt="">
                </div>
            </x-row>
</x-layout-section>
