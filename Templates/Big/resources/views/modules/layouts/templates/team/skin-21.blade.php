@php
/*
type: layout
name: Team 21
position: 21
categories: Team
*/
@endphp

<style>
    .team-21 {
        .speakers-thumb {
            position: relative;
        }

        .speakers-thumb-small {
            margin-top: 24px;
        }

        .speakers-thumb:hover .speakers-info::before {
            background-color: var(--mw-primary-color);
            width: 100%;
            padding: 15px;
        }

        .speakers-thumb:hover .speakers-title,
        .speakers-thumb:hover .speakers-text {
            color: #fff;
        }

        .speakers-info {
            background-color: #fff;
            box-shadow: 0 1rem 3rem rgba(0,0,0,.175);
            position: absolute;
            bottom: 0;
            right: 0;
            left: 0;
            margin: 20px;
            padding: 10px 15px;
        }

        .speakers-info::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            background-color: var(--mw-primary-color);
            width: 5px;
            height: 100%;
            transition: all 1s;
        }

        .speakers-image {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            object-fit: cover;
        }

        .speakers-image.speakers-image-small {
            min-height: 250px;
        }

        .speakers-image.speakers-image-large {
            min-height: 400px;
        }

        .speakers-text-info {
            padding: 100px;
        }

        .speakers-title,
        .speakers-text {
            position: relative;
        }

        .speakers-text {
            font-size: 12px;
            text-transform: uppercase;
        }

        .speakers-featured-text {
            background-color: var(--mw-primary-color);
            border-radius: 4px;
            color: #fff;
            position: absolute;
            top: 0;
            right: 0;
            font-size: 12px;
            text-transform: uppercase;
            margin: 10px;
            padding: 4px 12px;
        }

        .speakers-thumb .social-icon {
            position: absolute;
            right: 0;
            bottom: 0;
            margin: 15px;
            top: 0;
        }

        .speakers-thumb .social-icon {
            opacity: 0;
            transition: opacity all 1s;
        }

        .speakers-thumb:hover .social-icon {
            opacity: 1;
            transition-delay: 1s;
        }

        .speakers-thumb li {
            margin: 0 !important;
        }

        .speakers-thumb a i:before {
            color: #000;
            font-size: 18px !important;
        }

        .sponsor-image {
            max-width: 130px;
            margin: 10px auto;
            height: auto;
        }
    }
</style>

@php
if (!isset($classes['padding_top'])) {
    $classes['padding_top'] = '';
}
if (!isset($classes['padding_bottom'])) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section team-21 {{ $layout_classes }}">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" height="100px" id="spacer-layout--{{ $params['id'] }}-top" />

    <div class="mw-layout-container container no-element edit safe-mode " field="layout-team-skin-21-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-lg-6 col-12 d-flex flex-column justify-content-center align-items-center allow-select">
                <div class="speakers-text-info">
                    <h2 data-mwplaceholder="<?php _e('Enter title here'); ?>">Our <u class="text-info">Speakers</u></h2>
                    <p data-mwplaceholder="<?php _e('Enter text here'); ?>">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut dolore</p>
                </div>
            </div>

            <div class="col-lg-6 col-12 cloneable element allow-select">
                <div class="speakers-thumb">
                    <img class="speakers-image speakers-image-large" loading="lazy" src="{{ asset('templates/big/img/layouts/teamcard/1.jpg') }}" alt="" />
                    <small class="speakers-featured-text d-block background-color-element element">Featured</small>
                    <div class="speakers-info background-color-element element">
                        <h5 data-mwplaceholder="<?php _e('Enter title here'); ?>" class="speakers-title font-weight-bold mb-0">Logan Wilson</h5>
                        <p data-mwplaceholder="<?php _e('Enter text here'); ?>" class="speakers-text mb-0">CEO / Founder</p>
                        <module type="social_links" class="social-icon" />
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-12 allow-select">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-12 cloneable element">
                        <div class="speakers-thumb speakers-thumb-small">
                            <img class="speakers-image speakers-image-small" loading="lazy" src="{{ asset('templates/big/img/layouts/teamcard/1.jpg') }}" alt="" />
                            <div class="speakers-info background-color-element element">
                                <h5 data-mwplaceholder="<?php _e('Enter title here'); ?>" class="speakers-title font-weight-bold mb-0">Natalie</h5>
                                <p data-mwplaceholder="<?php _e('Enter text here'); ?>" class="speakers-text mb-0">Event Planner</p>
                                <module type="social_links" class="social-icon" />
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 cloneable element">
                        <div class="speakers-thumb speakers-thumb-small">
                            <img class="speakers-image speakers-image-small" loading="lazy" src="{{ asset('templates/big/img/layouts/teamcard/2.jpg') }}" alt="" />
                            <div class="speakers-info background-color-element element">
                                <h5 data-mwplaceholder="<?php _e('Enter title here'); ?>" class="speakers-title font-weight-bold mb-0">Thomas</h5>
                                <p data-mwplaceholder="<?php _e('Enter text here'); ?>" class="speakers-text mb-0">Startup Coach</p>
                                <module type="social_links" class="social-icon" />
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 cloneable element">
                        <div class="speakers-thumb speakers-thumb-small">
                            <img class="speakers-image speakers-image-small" loading="lazy" src="{{ asset('templates/big/img/layouts/teamcard/4.jpg') }}" alt="" />
                            <div class="speakers-info background-color-element element">
                                <h5 data-mwplaceholder="<?php _e('Enter title here'); ?>" class="speakers-title font-weight-bold mb-0">Isabella</h5>
                                <p data-mwplaceholder="<?php _e('Enter text here'); ?>" class="speakers-text mb-0">Event Manager</p>
                                <module type="social_links" class="social-icon" />
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 cloneable element">
                        <div class="speakers-thumb speakers-thumb-small">
                            <img class="speakers-image speakers-image-small" loading="lazy" src="{{ asset('templates/big/img/layouts/teamcard/3.jpg') }}" alt="" />
                            <div class="speakers-info background-color-element element">
                                <h5 data-mwplaceholder="<?php _e('Enter title here'); ?>" class="speakers-title font-weight-bold mb-0">Samantha</h5>
                                <p data-mwplaceholder="<?php _e('Enter text here'); ?>" class="speakers-text mb-0">Top Level Speaker</p>
                                <module type="social_links" class="social-icon" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
