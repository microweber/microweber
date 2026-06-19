{{--
type: layout

name: Misc 1

position: 1

categories: Misc
--}}

<style>
    .mw-ui-btn-nav.mw-ui-btn-nav-tabs.df {
        display: flex;
        flex-direction: column;
        width: 300px;
    }

    .mw-ui-btn.df {
        justify-content: start;
        margin-bottom: 5px;
        margin-right: 5px;
        border-radius: 0!important;
        height: 60px;
    }

    .mw-ui-btn.df i {
        margin-right: 10px;
    }
    .mw-ui-box {
        display: table-cell;
        border: none;
        box-shadow: none !important;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-misc-skin-1"
    container-class="mw-layout-container no-element container edit"
>
    <x-row>
                <div class="col-12">
                    <module type="tabs" default_content="1" class="tabs" class="d-flex flex-column"/>
                </div>
            </x-row>
</x-layout-section>
