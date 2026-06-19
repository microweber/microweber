{{--
type: layout
name: Call to action 22
position: 22
categories: Call to Action
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-call-to-action-skin-22"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row>
                <div class="col-12 col-sm-10 col-lg-8 col-lg-6 mx-auto text-center regular-mode">
                    <h1 data-mwplaceholder="{{ _e('Enter title here') }}">Make a Reservation</h1>
                    <p data-mwplaceholder="{{ _e('Enter title here') }}">Please fill the form below to make an online reservation</p>

                    <module type="contact_form" template="skin-1" />
                </div>
            </x-row>
</x-layout-section>
