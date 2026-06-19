{{--
 type: layout
 name: Free Element Container
 position: 1000
 categories: Design
--}}

<style>
    #mw-free-layout-container-{{ $params['id'] }}{
        min-height: 300px;
        min-height: max(300px, calc(100vh - 500px));
        position:relative;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    :has-spacers="false"
    container-class="mw-layout-container no-element"
>
    <div
            id="mw-free-layout-container-{{ $params['id'] }}"
            class="mw-layout-container mw-free-layout-container no-element allow-select">
            <div
                class="edit "
                field="layout-content-free-element-container-{{ $params['id'] }}"
                rel="module">

            </div>
        </div>
</x-layout-section>
