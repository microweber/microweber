{{--
type: layout
name: Background 6
position: 6
categories: Animated Backgrounds
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? 'pt-5';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? 'pb-5';
    $layout_classes = ($layout_classes ?? '') . ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<div class="animation-backgrounds-6">
    <section class="section bg-animation py-0 position-relative h-100vh" style="background-color: unset;">
        <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />

        <div class="stars">
            @for ($i = 0; $i < 40; $i++)
                <div class="star"></div>
            @endfor
        </div>

        <div class="container-fluid mh-100vh d-flex align-items-center {{ $layout_classes }} edit" field="layout-animation-bg-skin-6-{{ $params['id'] ?? '' }}" rel="module">
            <div class="mw-layout-container no-element row allow-select">
                <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="header-section-title mb-3" style="color: #ffffff;">Describe your company </h1>
                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="header-section-p" style="color: #ffffff;">Describe your company and services with few words and explain why you are the best choice.</p>
                <br>
                <module type="btn" button_style="btn-primary" text="Discover more"/>
            </div>
        </div>
    </section>
</div>
