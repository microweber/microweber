{{--
type: layout

name: Misc 9

position: 9

categories: Misc
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-misc-skin-9"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row class="col-12 py-5 text-center justify-content-center">
                <div class="col-6 col-lg-4 mb-5 cloneable element safe-mode background-color-element align-self-center">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/Amazon2.png') }}" alt="">
                    <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="pt-5">{{ _lang("Heading One") }}</h5>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">{{ _lang("Sony laptops are among the most well-known laptops on today's market.") }}</p>
                </div>
                <div class="col-6 col-lg-4 mb-5 cloneable element safe-mode background-color-element">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/Google2.png') }}" alt="">
                    <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="pt-5">{{ _lang("Heading Two") }}</h5>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">{{ _lang("Once the printer ink runs dry it has to be replaced with another.") }}</p>
                </div>
                <div class="col-6 col-lg-4 mb-5 cloneable element safe-mode background-color-element">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/Logitech.png') }}" alt="">
                    <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="pt-5">{{ _lang("Heading Three") }}</h5>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">{{ _lang("Accessories: Here you can find the best computer accessory.") }}</p>
                </div>
            </x-row>
</x-layout-section>
