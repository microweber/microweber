{{--
type: layout
name: Grid 13
position: 13
categories: Grids
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section layouts-grids-background"
    field-name="layout-grids-skin-13"
    container-class="mw-layout-container no-element container safe-mode edit"
>
    <x-row>
                <div class="col-12 col-lg-8 mb-2 cloneable element safe-mode">
                    <div class="cube">
                        <h3 data-mwplaceholder="{{ __('Enter title here') }}">The Amazing Hubble</h3>
                        <p data-mwplaceholder="{{ __('Enter title here') }}">When television was young, there was a hugely popular show based on the still popular functional character of Superman. The opening of that show had a familiar phrase that went.</p>
                    </div>
                </div>
                <div class="col-12 col-lg-4 mb-2 cloneable element safe-mode">
                    <div class="cube">
                        <h3 data-mwplaceholder="{{ __('Enter title here') }}">Radio Astronomy</h3>
                        <p data-mwplaceholder="{{ __('Enter title here') }}">There is a lot of exciting stuff going on in the stars above us that make astronomy so much fun.</p>
                    </div>
                </div>
            </x-row>
</x-layout-section>
