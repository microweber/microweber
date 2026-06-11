{{--
type: layout
name: Call to action 12
position: 12
categories: Call to Action
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section form-control-outline-dark"
    field-name="layout-call-to-action-skin-12"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row>
                <div class="col-12 col-sm-10 col-lg-12 col-lg-12 py-2 d-block d-lg-flex justify-content-between align-items-center">
                    <div class="col-md-4 py-4 text-center text-lg-start mt-4 pt-5 regular-mode">
                        <h5 data-mwplaceholder="{{ _e('Enter title here') }}" class="m-0">Leave your details and we will
                            <br> call you</h5>
                    </div>

                    <div class="col-md-8 py-4 text-center text-lg-end">
                        <module type="contact_form" template="subscribe-6"/>
                    </div>
                </div>
            </x-row>
</x-layout-section>
