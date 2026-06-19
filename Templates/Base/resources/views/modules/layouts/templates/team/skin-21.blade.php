{{--
type: layout
name: Team Cards
position: 21
categories: Team
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-team-skin-21"
    default-padding-bottom="pb-0"
    container-class="mw-layout-container no-element container edit"
>
    <x-row class="text-center">
                <div class="col-12 col-lg-10 col-lg-8 mx-auto mb-3">
                    <h3>Meet our Team</h3>
                    <p>The talented people behind our success.</p>
                </div>
            </x-row>
            <module type="teamcard" template="skin-19"/>
</x-layout-section>
