{{--
type: layout
name: Call to action 1
position: 1
categories: Call to Action
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-call-to-action-skin-1"
>
    <x-cta layout="inline" align="start">
        <x-slot:heading>
            <div class="regular-mode">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Start your free trial now, with a simple registration.</h3>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">No credit card required.</p>
            </div>
        </x-slot:heading>

        <div class="d-flex align-items-center regular-mode">
            <module type="btn" button_style="btn-outline-primary" text="Log In" class="mx-2"/>
            <module type="btn" button_style="btn-link" text="Register Now" class="mx-2"/>
        </div>
    </x-cta>
</x-layout-section>
