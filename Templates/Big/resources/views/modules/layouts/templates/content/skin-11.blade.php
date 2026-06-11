{{--
type: layout

name: Content 11 - Parallax

position: 11

categories: Content
--}}

<x-layout-section
    :has-background="false"
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section py-0 mw-layout-parallax mh-550 d-flex align-items-center justify-content-center mw-layout-dark-background"
    default-padding-top="pt-10"
    default-padding-bottom="pb-10"
    container-class="mw-layout-container no-element"
>
    <module type="background" data-parallax="true" data-overlay-x="1" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" id="background-layout--{{ $params['id'] }}" />
    <div class="container mw-layout-overlay-container mw-layout-container safe-mode no-element {{ $layout_classes ?? '' }} edit " field="layout-content-skin-11-{{ $params['id'] }}" rel="module">

                <h3 data-mwplaceholder="{{ _e('Enter title here') }}">
                    First off, you will need to set a budget
                    <br/>
                    for your new purchase before deciding whether to shop for notebook or desktop computers. Many offices use.
                </h3>
                <br/>
                <module type="btn" button_style="btn-primary" text="Learn more"/>


        </div>
</x-layout-section>
