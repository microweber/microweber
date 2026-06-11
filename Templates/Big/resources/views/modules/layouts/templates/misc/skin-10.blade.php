{{--
type: layout

name: Misc 10

position: 10

categories: Misc
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-misc-skin-10"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row>
                <div class="col-sm-8 mx-auto">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="text-center">{{ _lang("How To Look Up") }}.</h3>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}" class="text-center font-weight-bold">{{ _lang("Usage of the Internet is becoming more common due to rapid advancement of technology and the power of globalization.") }}</p>
                </div>
                <x-row class="text-center py-5">
                    <div class="col-6 col-lg-2 me-auto cloneable element safe-mode align-self-center">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/Amazon2.png') }}" alt="">
                    </div>
                    <div class="col-6 col-lg-2 mx-auto cloneable element safe-mode">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/Facebook2.png') }}" alt="">
                    </div>
                    <div class="col-6 col-lg-2 mx-auto cloneable element safe-mode">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/Google2.png') }}" alt="">
                    </div>
                    <div class="col-6 col-lg-2 mx-auto cloneable element safe-mode">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/LinkedIn2.png') }}" alt="">
                    </div>
                    <div class="col-6 col-lg-2 mx-auto cloneable element safe-mode">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/Logitech.png') }}" alt="">
                    </div>
                </x-row>
                <div class="text-center">
                    <module type="btn" text="Learn More" button_style="btn-primary" button_size=" " />
                </div>
            </x-row>
</x-layout-section>
