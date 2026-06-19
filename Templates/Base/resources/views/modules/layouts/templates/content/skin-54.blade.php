{{--
type: layout

name: Content 54

position: 54

categories: Content
--}}

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section"
    field-name="layout-content-skin-54"
    container-class="mw-layout-container container safe-mode no-element edit"
>
    <x-row>
                <div class="col-12 col-lg-10 col-lg-6 mx-auto text-center allow-drop allow-select ">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}">Your Title Here</h3>
                    <p data-mwplaceholder="{{ _e('Enter title here') }}">Ah, the technical interview. Nothing like it. Not only does it cause anxiety,
                        <br> but it causes anxiety for several different reasons.</p>
                    <br/>

                    <div class="d-flex align-items-center justify-content-center ">
                        <div class="cloneable element mx-2">
                            <module type="btn" button_style="btn-primary"  text="Buy"/>
                        </div>
                        <div class="cloneable element mx-2">
                            <module type="btn" button_style="btn-link" text="Learn More"/>
                        </div>
                    </div>
                </div>
            </x-row>
</x-layout-section>
