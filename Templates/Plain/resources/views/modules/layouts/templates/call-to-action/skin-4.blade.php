{{--
type: layout
name: Call to action 4
position: 4
categories: Call to Action
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-call-to-action-skin-4"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row class="d-flex justify-content-between">
                <div class="col-12 col-sm-10 col-lg-5 text-center text-lg-start d-flex flex-column justify-content-center regular-mode">
                    <h6 data-mwplaceholder="{{ _e('Enter title here') }}">Shooting Stars</h6>
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-0">What If They Let You Run The Hubble</h3>
                </div>

                <div class="col-12 col-sm-10 col-lg-6 d-flex align-items-center justify-content-lg-end justify-content-center mt-2 mt-sm-0 mx-auto">
                    <div class="d-flex regular-mode">
                        <a href="#" class="ms-2 w-150"><img loading="lazy" src="{{ asset('templates/big/img/layouts/content-39-1.jpg') }}" alt=""></a>
                        <a href="#" class="ms-2 w-150"><img loading="lazy" src="{{ asset('templates/big/img/layouts/content-39-2.jpg') }}" alt=""></a>
                    </div>
                </div>
            </x-row>
</x-layout-section>
