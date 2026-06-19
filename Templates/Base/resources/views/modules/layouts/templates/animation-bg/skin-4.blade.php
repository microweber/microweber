{{--
type: layout
name: Background 4
position: 4
categories: Animated Backgrounds
--}}

<div class="animation-backgrounds-4 mw-layout-dark-background">
<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section bg-animation py-0 position-relative h-100vh"
    background-attrs='data-background-color="#3E1E68"'
    :has-spacers="false"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="mw-layout-container no-element"
>
    <div class="container mh-100vh d-flex align-items-center" style="z-index: 2">
                <div class="mw-layout-container no-element row allow-select edit" field="layout-animation-bg-skin-4-{{ $params['id'] ?? '' }}" rel="module">
                    <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="header-section-title mb-3">Describe your company </h1>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}" class="header-section-p">Describe your company and services with few words and explain why you are the best choice.</p>
                    <br>
                    <module type="btn" button_style="btn-link" text="See more"/>
                </div>
            </div>

            <div class="background">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
</x-layout-section>
