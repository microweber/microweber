{{--
type: layout
name: Background 3
position: 3
categories: Animated Backgrounds
--}}

<style>

</style>

<div class="animation-backgrounds-3 mw-layout-dark-background">
<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section bg-animation py-0 position-relative"
    :has-spacers="false"
    container-class="background"
>
    <div class="container mh-100vh d-flex align-items-center justify-content-center">
                    <div class="mw-layout-container no-element row text-center allow-select edit" field="layout-animation-bg-skin-3-{{ $params['id'] ?? '' }}" rel="module">
                        <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="header-section-title mb-3">Describe your company </h1>
                        <p data-mwplaceholder="{{ _e('Enter text here') }}" class="header-section-p">Describe your company and services with few words and explain why you are the best choice.</p>
                        <br>
                        <module type="btn" button_style="btn-primary" text="See more"/>
                    </div>
                </div>

                <div class="cube"></div>
                <div class="cube"></div>
                <div class="cube"></div>
                <div class="cube"></div>
                <div class="cube"></div>
</x-layout-section>
