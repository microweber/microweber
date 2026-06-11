{{--
type: layout
name: Background 6
position: 6
categories: Animated Backgrounds
--}}

<div class="animation-backgrounds-6">
<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section bg-animation py-0 position-relative h-100vh"
    :has-spacers="false"
    default-padding-top="pt-5"
    default-padding-bottom="pb-5"
    container-class="stars"
>
    @for ($i = 0; $i < 40; $i++)
                    <div class="star"></div>
                @endfor
            </div>

            <div class="container-fluid mh-100vh d-flex align-items-center {{ $layout_classes ?? '' }} edit" field="layout-animation-bg-skin-6-{{ $params['id'] ?? '' }}" rel="module">
                <div class="mw-layout-container no-element row allow-select">
                    <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="header-section-title mb-3" style="color: #ffffff;">Describe your company </h1>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}" class="header-section-p" style="color: #ffffff;">Describe your company and services with few words and explain why you are the best choice.</p>
                    <br>
                    <module type="btn" button_style="btn-primary" text="Discover more"/>
                </div>
</x-layout-section>
