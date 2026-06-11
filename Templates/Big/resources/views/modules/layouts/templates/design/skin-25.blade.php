{{--
type: layout
name: Design 25
position: 125
categories: Design
--}}

<style>
    .flex-right, .flex-tag {
        display: flex;
    }

    .flex-tag {
        align-items: center;
        background-color: var(--mw-primary-color);
        border-color: #c7c3cf;
        border-radius: 30px;
        border-width: 2px;
        column-gap: 10px;
        margin-bottom: 30px;
        padding: 10px 20px;
    }

    .mw-new-25-title-tag {
        color: #fff;
        font-size: 12px;
        font-weight: 500;
        line-height: 1.1;
    }

    .mw-new-25-title {
        font-size: 38px;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 5px;
        margin-top: 0;
    }

    @media screen and (max-width: 991px) {
        .mw-new-25-title {
            font-size: 45px;
        }
    }

    @media screen and (max-width: 479px) {
        .mw-new-25-title {
            font-size: 34px;
        }
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section mw-new-layouts-25"
    field-name="layout-new-layouts-skin-25"
    container-class="mw-layout-container container no-element edit safe-mode"
>
    <x-row>
                <div class="col-12">
                    <div class="flex-right">
                        <div class="flex-tag background-color-element element" style="opacity: 1;">
                            <span class="mw-new-25-title-tag">TESTIMONIALS</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="mw-new-25-title">WHAT CLIENTS ARE
                            <br> SAYING ABOUT US?
                        </h2>
                    </div>

                    <module type="testimonials" template="skin-22"/>
                </div>
            </x-row>
</x-layout-section>
