{{--
type: layout
name: Grid 12
position: 12
categories: Grids
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-grids-skin-12"
    container-class="mw-layout-container no-element container safe-mode edit"
>
    <x-row>
                <div class="col-12 col-lg-6 mb-2 cloneable element safe-mode layouts-grids-background">
                    <div class="cube">
                        <h3 data-mwplaceholder="{{ __('Enter title here') }}">Look Up In The Sky</h3>
                        <p data-mwplaceholder="{{ __('Enter text here') }}">In the history of modern astronomy, there is probably no one greater leap forward than the building and launch of the space telescope known as the Hubble. While NASA has had many ups and downs.</p>
                    </div>
                </div>
                <div class="col-12 col-lg-6 mb-2 cloneable element safe-mode layouts-grids-background">
                    <div class="cube">
                        <h3 data-mwplaceholder="{{ __('Enter title here') }}">How To Look Up</h3>
                        <p data-mwplaceholder="{{ __('Enter text here') }}">In the history of modern astronomy, there is probably no one greater leap forward than the building and launch of the space telescope known as the Hubble. While NASA has had many ups and downs.</p>
                    </div>
                </div>
            </x-row>
</x-layout-section>
