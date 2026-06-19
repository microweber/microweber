{{--
type: layout
name: Call to action 5
position: 5
categories: Call to Action
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section form-control-outline-dark"
    field-name="layout-call-to-action-skin-5"
>
    <x-cta align="center">
        <x-slot:heading>
            <div class="col-12 col-sm-10 col-lg-10 col-lg-7 mx-auto regular-mode">
                <x-section-heading tag="h1">Your Title Here</x-section-heading>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">Subscribe for our newsletter to receive information about the new stuff.</p>
            </div>
        </x-slot:heading>

        <x-row class="mt-5">
            <div class="col-12 col-sm-10 col-lg-10 col-lg-7 mx-auto text-center">
                <module type="contact_form" template="subscribe-7"/>
            </div>
        </x-row>
    </x-cta>
</x-layout-section>
