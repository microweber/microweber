{{--
type: layout
name: Background 5
position: 5
categories: Animated Backgrounds
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? 'pt-5';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? 'pb-5';
    $layout_classes = ($layout_classes ?? '') . ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<div class="animation-backgrounds-5">
    <section class="section bg-animation py-0 mw-layout-dark-background position-relative h-100vh {{ $layout_classes }}">
        <module type="background" data-background-color="#213585" id="background-layout--{{ $params['id'] ?? '' }}" />

        <div class="container-fluid mh-100vh d-flex align-items-center">
            <div class="mw-layout-container no-element row allow-select edit" field="layout-animation-bg-skin-5-{{ $params['id'] ?? '' }}" rel="module">
                <h1 data-mwplaceholder="{{ _e('Enter title here') }}" class="header-section-title mb-3">Describe your company </h1>
                <p data-mwplaceholder="{{ _e('Enter text here') }}" class="header-section-p">Describe your company and services with few words and explain why you are the best choice.</p>
                <br>
                <module type="btn" button_style="btn-primary" text="Discover more"/>
            </div>
        </div>

        <div class="area">
            <ul class="circles">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
    </section>
</div>
