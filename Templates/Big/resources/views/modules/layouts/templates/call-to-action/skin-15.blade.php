{{--
type: layout
name: Call to action 15
position: 15
hidden: true
categories: Call to Action
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section form-control-outline-dark"
    field-name="layout-call-to-action-skin-15"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row class="d-flex align-items-center justify-content-center justify-content-lg-end text-center text-lg-start">
                <div class="col-12 col-sm-10 col-lg-4 py-4 regular-mode">
                    <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="m-0">Sign up to newsletter</h1>
                </div>

                <div class="col-12 col-sm-10 col-lg-8 py-4">
                    <module type="contact_form" template="subscribe-6"/>
                </div>
            </x-row>
</x-layout-section>
