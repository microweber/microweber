{{-- 
type: layout
name: Testimonial 26
position: 26
categories: Testimonials
--}}

@php
$classes['padding_top'] = $classes['padding_top'] ?? '';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .testimonials-26 .testimonials-26-section-heading {
        margin-bottom: 0px;
        margin-left: 60px;
        vertical-align: middle;
    }

    .testimonials-26 .testimonials-26-section-heading p {
        margin-top: 50px;
    }

    .testimonials-26 .testimonials-26-item {
        background-color: #fff;
        border-radius: 25px;
    }

    .testimonials-26 .testimonials-26-item p {
        font-size: 18px;
        line-height: 40px;
        font-style: italic;
        font-weight: 300;
        margin-bottom: 40px;
    }

    .testimonials-26 .testimonials-26-item img {
        border-radius: 50%;
        max-width: 100px;
        float: left;
        margin-right: 25px;
    }

    .testimonials-26 .testimonials-26-item span {
        display: inline-block;
        margin-top: 20px;
        font-size: 15px;
    }

    .testimonials-26 .testimonials-26-item h4 {
        font-size: 22px;
        font-weight: 600;
        margin-top: 8px;
    }
</style>

<section class="section testimonials-26 {{ $layout_classes }}">
    <module type="background" data-background-color="#F1F0FE" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" height="150px;" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container edit" field="layout-testimonials-skin-26-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-lg-7">
                <module type="testimonials" template="skin-21" project_name="Testimonials 1"/>
            </div>
            <div class="col-lg-5 align-self-center mt-lg-0 mt-4 safe-mode">
                <div class="testimonials-26-section-heading regular-mode">
                    <p style="color: #7A6AD8; font-weight: bold;" data-mwplaceholder="<?php _e('Enter title here'); ?>">TESTIMONIALS</p>
                    <h3 data-mwplaceholder="<?php _e('Enter title here'); ?>">What they say about us?</h3>
                    <p data-mwplaceholder="<?php _e('Enter text here'); ?>">You can search free CSS templates on Google using different keywords such as template portfolio, template gallery, template blue color, etc.</p>
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" height="150px;" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
