{{--
type: layout
name: Call to action 3
position: 3
hidden: true
categories: Call to Action
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section form-control-outline-dark"
    field-name="layout-call-to-action-skin-3"
>
    <x-cta align="start">
        <x-slot:heading>
            <div class="regular-mode">
                <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="m-0">Get closer to the biggest</h1>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">Sign up for updates and new features</p>
            </div>
        </x-slot:heading>

        <module type="contact_form" template="subscribe-6"/>
    </x-cta>
</x-layout-section>
