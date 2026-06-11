{{--
type: layout
name: Call to action 24
position: 24
categories: Call to Action
--}}

<style>
    .flower-cta-box {
        background-color: #000000;
        border-radius: 50px 0;
    }

    .flower-cta-div-form .mb-3.d-flex.d-flex {
        margin-bottom: 0!important;
    }

    .flower-cta-div-form {
        padding: 10px;
        border-radius: 30px 0;
        background-color: white;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section ziza-cta-form-wrapper"
    field-name="layout-call-to-action-skin-24"
    container-class="mw-layout-container no-element container-fluid position-relative edit safe-mode"
>
    <x-row class="col-xl-10 col-12 mx-auto align-items-center justify-content-center flower-cta-box element background-color-element text-center py-5">
                <div class="col-12 col-xl-8 mx-auto regular-mode">
                    <h2 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-3" style="color: #ffffff;">Subscribe for Get Update Every <br> New Courses</h2>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}" class="mb-5" style="color: #ffffff;">586K Students daily learn with e-learning. Subscribe for new course</p>

                    <module class="w-100" type="contact_form" template="subscribe-6"/>
                </div>
            </x-row>
</x-layout-section>
