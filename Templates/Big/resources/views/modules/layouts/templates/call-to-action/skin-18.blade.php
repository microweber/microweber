{{--
type: layout
name: Call to action 18
position: 18
categories: Call to Action
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section form-control-outline-dark"
    field-name="layout-call-to-action-skin-18"
    container-class="mw-layout-container no-element edit safe-mode"
>
    <x-row>
                <div class="col-12">
                    <x-row class="d-flex justify-content-between">
                        <div class="col-12 col-lg-7 position-relative mh-600">
                            <div class="background-image-holder h-100" style="background-image: url('{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}'); background-size: contain;">
                                <div class="text-center text-lg-start cloneable element regular-mode mw-layout-dark-background">
                                    <div class="col-8 mx-auto d-flex h-100 justify-content-center flex-column py-5">
                                         <h4 data-mwplaceholder="{{ _e('Enter title here') }}">Space Shuttle as one of the greatest space exploration accomplishments</h4>
                                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-0">All users on MySpace will know that there are millions of people out there. Every day besides </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-5 cloneable element regular-mode background-color-element p-5">
                            <h5 data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-4">Sign up</h5>
                            <module type="contact_form" template="subscribe-2" />
                            <small data-mwplaceholder="{{ _e('Enter text here') }}" class="mt-4">Over time, even the most sophisticated, memory packed computer</small>
                        </div>
                    </x-row>
                </div>
            </x-row>
</x-layout-section>
