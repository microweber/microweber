{{--
type: layout
name: Grid 3
position: 3
categories: Grids
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-grids-skin-3"
    container-class="mw-layout-container no-element container safe-mode edit"
>
    <x-row>
                <div class="col-12 mb-2 cloneable element safe-mode layouts-grids-background">
                    <div class="cube-wrapper">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-7.jpg') }}" alt="">
                    </div>
                </div>
            </x-row>
</x-layout-section>
