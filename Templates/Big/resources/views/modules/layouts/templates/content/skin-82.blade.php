{{--
type: layout

    name: Content 82

    position: 82

    categories: Content
--}}

<style>
    @media (min-width: 1000px) {
        .content-82-white-box {
            position: absolute;
            top: 50%;
            transform: translate(-100px, -50%);
            z-index: 9;
            background: #fff;
            max-width: 900px !important;
            border: 1px solid rgba(33, 33, 33, 0.3);
        }
    }
    .content-82-white-box {
        padding: 50px 70px;
    }
</style>



<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section section-content-80 pb-0"
    field-name="layout-content-skin-82"
    container-class="mw-layout-container safe-mode no-element edit"
>
    <x-row>
                <div class="col-lg-6 col-xl-5 background-image-holder mh-700" style="background-image: url('{{ asset('templates/big/img/layouts/gallery-1-15.jpg') }}');"></div>

                <div class="col-lg-6 col-xl-7 position-relative">
                    <div class="content-82-white-box shadow-sm background-color-element element">
                        <h4 data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-3">Your awesome title</h4>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Update your audience on new developments and how you're overcoming challenges.</p>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                        <p></p>
                        <module type="btn" button_style="btn-primary" button_size="btn-md" text="Learn more"/>
                    </div>
                </div>
            </x-row>
</x-layout-section>
