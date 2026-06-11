{{--
 type: layout
 name: Free Element Fixed Container
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
            class="no-element">
            <div class="mw-layout-container mw-free-layout-container mw-free-layout-container-fixed">
                <div
                    class="edit "
                    field="layout-content-free-element-container-{{ $params['id'] }}-4"
                    rel="module">

                </div>
            </div>
        </div>
</x-layout-section>
