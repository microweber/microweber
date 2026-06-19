{{--
type: layout
name: Grid 4
position: 4
categories: Grids
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-grids-skin-4"
    container-class="mw-layout-container no-element container safe-mode edit"
>
    <x-row>
                <div class="col-sm mb-2 cloneable element safe-mode layouts-grids-background">
                    <div class="w-100 cube-wrapper">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-8.jpg') }}" alt="">
                    </div>
                </div>
                <div class="col-sm mb-2 cloneable element safe-mode layouts-grids-background">
                    <div class="w-100 cube-wrapper">
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-9.jpg') }}" alt="">
                    </div>
                </div>
            </x-row>
</x-layout-section>
