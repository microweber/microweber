{{--
type: layout

name: Content 43

position: 43

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-43"
    container-class="mw-layout-container container safe-mode no-element d-flex justify-content-center align-items-center edit"
>
    <div class="text-center">
                <x-row class="text-center">
                    <div class="col-12 col-lg-10 col-lg-8 mx-auto  regular-mode allow-select allow-drop">
                        <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Your Awesome Title</h3>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Are you considering buying a compatible inkjet cartridge for your printer?
                            <br> There are many reputed companies like Canon, Epson, Dell, and Lexmark.</p>
                        <br />
                        <br />
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-4.jpg') }}" alt=""/>
                    </div>
                </x-row>
            </div>
</x-layout-section>
