{{--
type: layout
name: Grid 1
position: 1
categories: Grids
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-grids-skin-1"
    container-class="mw-layout-container no-element container safe-mode edit"
>
    <x-row class="safe-mode">
                <div class="col-12 col-sm-8 safe-mode img-as-background" style="min-height: 350px;">
                    <img loading="lazy" class="me-sm-3" src="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" alt="">
                </div>
                <div class="col-12 col-sm-4 safe-mode img-as-background" style="min-height: 350px;">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-7.jpg') }}" alt="">
                </div>
            </x-row>
</x-layout-section>
