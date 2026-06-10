<?php

/*

type: layout

name: Content 83

position: 83

categories: Content

*/

?>

@if (!$classes['padding_top'])
    @php $classes['padding_top'] = ''; @endphp
@endif
@if (!$classes['padding_bottom'])
    @php $classes['padding_bottom'] = ''; @endphp
@endif

@php
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp


<style>
    .profile-body p {
        margin-bottom: 0;
    }

    .profile-body p:nth-of-type(even) {
        background: #fff;
    }

    .mw-content-83-about-image {
        border-radius: 20px;
    }

    .mw-content-83-about-thumb {
        padding-right: 20px;
        padding-left: 20px;
    }

    .mw-content-83-section-title-wrap {
        background-color: var(--mw-primary-color);
        border-radius: 10px;
        padding: 10px 30px;
    }

    .mw-content-83-avatar-image-wrapper img {
        border-radius: 100px;
        width: 160px !important;
        height: 160px !important;
        object-fit: cover;
    }
</style>

<section class="section mw-content-83-about {{ $layout_classes }} section-content-83 pb-0 ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />

    <div class=" mw-layout-container safe-mode no-element edit container " field="layout-content-skin-83-{{ $params['id'] }}" rel="module">

        <div class="row">

            <div class="col-lg-6 col-12 safe-mode allow-select">
                <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/couple-working-from-home-together-sofa.jpg') }}" class="mw-content-83-about-image img-fluid" alt=""/>
            </div>

            <div class="col-lg-6 col-12 mt-5 mt-lg-0 allow-select">
                <div class="mw-content-83-about-thumb safe-mode">

                    <div class="mw-content-83-section-title-wrap background-color-element element d-flex justify-content-end align-items-center mb-4 mw-content-83-avatar-image-wrapper">
                        <h2 data-mwplaceholder="@lang('Enter title here')" class="text-white me-4 mb-0">My Story</h2>
                        <img loading="lazy" src="{{ asset('templates/big/img/layouts/freelancer/happy-bearded-young-man.jpg') }}" alt=""/>

            </div>

                    <h3 data-mwplaceholder="@lang('Enter title here')" class="pt-2 mb-3 font-weight-bold">a little bit about Joshua</h3>

                    <p data-mwplaceholder="@lang('Enter title here')">This one-page HTML portfolio is provided by
                        <a href="" target="_blank">CMS</a>.
                        This layout is based on Bootstrap v5.1.3 CSS and JS libraries. Image credits go to
                        <a href="https://unsplash.com" target="_blank">Unsplash</a> and <a href="https://freepik.com" target="_blank">FreePik</a>
                        for images used in this page.
                    </p>

                    <p data-mwplaceholder="@lang('Enter title here')">You are allowed to use this template for your websites. You are not allowed to redistribute the template ZIP file on any other website. Please <a href="" target="_blank">contact us</a> for more info.</p>
                </div>
            </div>

        </div>
    </div>


    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />

</section>
