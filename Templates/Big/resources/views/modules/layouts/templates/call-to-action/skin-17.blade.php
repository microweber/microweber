{{--
type: layout
name: Call to action 17
position: 17
categories: Call to Action
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section form-control-outline-dark"
    field-name="layout-call-to-action-skin-17"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row>
                <div class="col-12 col-sm-10 col-lg-10 col-lg-7 mx-auto text-center regular-mode">
                    <h1 data-mwplaceholder="{{ _e('Enter title here') }}">Sign Up</h1>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">Planning to visit Las Vegas or any other vacational resort where casinos Planning to visit Las Vegas or any other vacational resort where casinos </p>
                </div>
            </x-row>

            <div><br/><br/></div>

            <x-row>
                <div class="col-12 col-sm-10 col-lg-8 col-lg-4 mx-auto safe-mode">
                    <module type="contact_form" template="skin-2" />
                </div>
            </x-row>
</x-layout-section>
