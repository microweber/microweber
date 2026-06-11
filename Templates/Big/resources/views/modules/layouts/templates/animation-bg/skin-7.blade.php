{{--
type: layout
name: Background 7
position: 7
categories: Animated Backgrounds
--}}

<div class="animation-backgrounds-7">
<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section bg-animation py-0 position-relative h-100vh"
    :has-spacers="false"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="mw-layout-container no-element"
>
    <article class="wrapper">
                <div class="container-fluid mh-100vh d-flex align-items-center {{ $layout_classes ?? '' }}" field="layout-animation-bg-skin-7-{{ $params['id'] ?? '' }}" rel="module">
                    <div class="mw-layout-container no-element row allow-select edit">
                        <div class="col-12 col-lg-10 mx-auto text-white">
                            <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="header-section-title mb-3" style="color: #ffffff;">Describe your company </h1>
                            <p data-mwplaceholder="{{ _e('Enter text here') }}" class="header-section-p" style="color: #ffffff;">Describe your company and services with few words and explain why you are the best choice.</p>
                            <br>
                            <module type="btn" button_style="btn-primary" text="See more"/>
                        </div>
                    </div>
                </div>
            </article>
</x-layout-section>
