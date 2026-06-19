{{--
type: layout
name: Call to action 10
position: 10
categories: Call to Action
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-call-to-action-skin-10"
>
    <x-cta layout="inline" align="start">
        <x-slot:heading>
            <div class="regular-mode">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Start your free trial now, with a simple registration.</h3>
            </div>
        </x-slot:heading>

        <module type="btn" button_style="btn-primary px-5" text="Button" class="ms-2"/>
    </x-cta>
</x-layout-section>
