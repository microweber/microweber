{{--
type: layout
name: Background 7
position: 7
categories: Animated Backgrounds
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? 'pt-5';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? 'pb-5';
    $layout_classes = ($layout_classes ?? '') . ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<div class="animation-backgrounds-7">
    <section class="section bg-animation py-0 position-relative h-100vh" style="background-color: unset;">
        <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />
        <article class="wrapper">
            <div class="container-fluid mh-100vh d-flex align-items-center {{ $layout_classes }}" field="layout-animation-bg-skin-7-{{ $params['id'] ?? '' }}" rel="module">
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
    </section>
</div>
