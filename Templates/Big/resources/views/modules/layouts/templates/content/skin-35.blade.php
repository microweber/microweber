{{--
type: layout

name: Content 35

position: 35

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-35"
    container-class="mw-layout-container container safe-mode no-element safe-mode edit"
>
    <x-row class="nodrop no-select">
                <div class="col-12 col-sm-10 col-lg-6 mx-auto pb-5 pe-lg-5 text-center text-lg-start d-flex align-items-center cloneable ">
                    <div class="regular-mode allow-drop allow-select" style="min-height: 150px; width: 100%">
                        <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-4">Your Awesome Title Here</h3>

                        <p data-mwplaceholder="{{ _e('Enter title here') }}">The large-screen TV has come a long way from those faded-out behemoths of old that took up half your living room and never really produced a picture of decent quality. Now, however, especially in combination with HDTV, you </p>
                    </div>
                </div>

                <div class="col-12 col-sm-10 col-lg-6 mx-auto cloneable   ">
                    <div class="text-center text-lg-end pb-5  allow-drop allow-select" style="min-height: 150px;width: 100%">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-12.jpg') }}" alt=""/>
                    </div>
                </div>
            </x-row>
</x-layout-section>
