{{--
type: layout
name: Background 1
position: 1
categories: Animated Backgrounds
--}}

<div class="animation-backgrounds-1 mw-layout-dark-background">
<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section bg-animation py-0"
    field-name="layout-animation-bg-skin-1"
    :has-spacers="false"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="container mh-100vh d-flex align-items-center justify-content-center mw-layout-container no-element row text-center allow-select edit"
    :use-container="false"
>
    <div class="container mh-100vh d-flex align-items-center justify-content-center">
        <div class="mw-layout-container no-element row text-center allow-select edit">
            <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="header-section-title mb-3">Describe your company </h1>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}" class="header-section-p">Describe your company and services with few words and explain why you are the best choice.</p>
                            <br>
                            <module type="btn" button_style="btn-primary" button_size="btn-lg px-5" text="Call to action"/>
        </div>
    </div>
</x-layout-section>
</div>
