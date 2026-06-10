{{--
type: layout
name: Background 1
position: 1
categories: Animated Backgrounds
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? 'pt-5';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? 'pb-5';
    $layout_classes = ($layout_classes ?? '') . ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<div class="animation-backgrounds-1 mw-layout-dark-background">
    <section class="section bg-animation py-0  {{ $layout_classes }}">
        <module type="background" id="background-layout--{{ $params['id'] ?? '' }}" />

        <div class="container mh-100vh d-flex align-items-center justify-content-center">
            <div class="mw-layout-container no-element row text-center allow-select edit" field="layout-animation-bg-skin-1-{{ $params['id'] ?? '' }}" rel="module">
                <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="header-section-title mb-3">Describe your company </h1>
                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="header-section-p">Describe your company and services with few words and explain why you are the best choice.</p>
                <br>
                <module type="btn" button_style="btn-primary" button_size="btn-lg px-5" text="Call to action"/>
            </div>
        </div>
    </section>
</div>
