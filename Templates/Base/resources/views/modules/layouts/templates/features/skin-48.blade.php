{{--
 type: layout
 name: Feature 48
 position: 48
 categories: Features
--}}

<style>
    .features-48 .background-image-holder:before {
        position: absolute;
        left: 0;
        top: 0;
        background: rgba(8,18,49,0.5);
        content: '';
        width: 100%;
        height: 100%;
        z-index: 1;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section features-48"
    field-name="layout-feature-skin-48"
    container-class="mw-layout-container no-element container edit safe-mode"
>
    <x-row class="text-center mx-auto">
                <div class="col-lg-6 col-xxl-4 col-12 cloneable element ">
                    <div class="mw-layout-container background-color-element position-relative background-image-holder mh-450 mw-layout-dark-background d-flex align-items-center justify-content-center" style="background-image: url('{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}');">
                        <div class="regular-mode">
                            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">FEATURE TITLE</h5>
                            <p></p>
                            <module type="btn" button_style="btn-link" text="Learn more"/>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xxl-4 col-12 cloneable element ">
                    <div class="mw-layout-container background-color-element position-relative background-image-holder mh-450 mw-layout-dark-background d-flex align-items-center justify-content-center" style="background-image: url('{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}');>
                        <div class="regular-mode">
                            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">FEATURE TITLE</h5>
                            <p></p>
                            <module type="btn" button_style="btn-link" text="Learn more"/>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xxl-4 col-12 cloneable element ">
                    <div class="mw-layout-container background-color-element position-relative background-image-holder mh-450 mw-layout-dark-background d-flex align-items-center justify-content-center" style="background-image: url('{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}');">
                        <div class="regular-mode">
                            <h5 data-mwplaceholder="{{ _e('Enter title here') }}">FEATURE TITLE</h5>
                            <p></p>
                            <module type="btn" button_style="btn-link" text="Learn more"/>
                        </div>
                    </div>
                </div>
            </x-row>
</x-layout-section>
