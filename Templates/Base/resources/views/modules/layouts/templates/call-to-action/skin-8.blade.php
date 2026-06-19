{{--
type: layout
name: Call to action 8
position: 8
hidden: true
categories: Call to Action
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-call-to-action-skin-8"
>
    <x-cta layout="inline" align="start">
        <x-slot:heading>
            <div class="regular-mode">
                <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Space The Final Frontier</h3>
            </div>
        </x-slot:heading>

        <div class="d-flex">
            <module type="btn" button_style="btn-outline" text="Button" class="ms-3"/>
            <module type="btn" button_style="btn-primary" text="Button" class="ms-3"/>
        </div>
    </x-cta>
</x-layout-section>
