{{--
type: layout
name: Team 10
position: 10
categories: Team
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-team-skin-10"
    default-padding-bottom="pb-0"
    container-class="mw-layout-container no-element container edit"
>
    <x-row>
                <div class="mb-5">
                    <h3>Meet our Team</h3>
                    <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. <br> Deserunt doloribus ducimus expedita labore non odit quibusdam repellendus sunt.</p>
                </div>
            </x-row>

            <module type="teamcard" template="skin-10"/>
</x-layout-section>
