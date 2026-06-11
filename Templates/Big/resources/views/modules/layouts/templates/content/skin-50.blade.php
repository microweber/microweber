{{--
type: layout

name: Content 50

position: 50

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-50"
    container-class="mw-layout-container container safe-mode no-element edit"
>
    <x-row class="d-flex justify-content-center justify-content-lg-between safe-mode  nodrop no-select">
                <div class="col-12 col-sm-10 col-lg-4 pb-5 text-center text-lg-start">

                    <div class=" regular-mode allow-drop allow-select" style="min-height:50px">
                        <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Your Awesome Title Here</h3>
                    </div>
                </div>

                <div class="col-12 col-sm-10 col-lg-7   safe-mode">

                    <div class=" regular-mode allow-drop allow-select">
                    <p data-mwplaceholder="{{ _e('Enter title here') }}">For business professionals caught between high OEM price and mediocre print and graphic output, there's a solution: Business Express's Eclipse line of compatible laser toner cartridges that meet </p>
                    <br />

                        <module type="btn" button_style="btn-primary" text="Find Out"/>
                    </div>
                </div>
            </x-row>
</x-layout-section>
