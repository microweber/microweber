@php
/*
type: layout
name: Header 12
position: 12
categories: Header
*/
@endphp

@php
$classes['padding_top'] = $classes['padding_top'] ?? 'pt-7';
$classes['padding_bottom'] = $classes['padding_bottom'] ?? 'pb-0';

$layout_classes = isset($layout_classes) ? $layout_classes : '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section py-0 d-flex align-items-center justify-content-center">
    <module type="background" />
    <div class="mw-layout-container py-4 container mw-header-section-mh-100vh d-flex align-items-center justify-content-center no-element edit " field="layout-header-skin-12-{{ $params['id'] ?? '' }}" rel="module">
        <div class="row text-center">
            <div class="col-12 safe-mode col-lg-8 mx-auto">
                <div class="allow-select">
                    <h1 data-mwplaceholder="@lang('Enter title here')" class="header-section-title mb-7">Describe your company </h1>
                    <p data-mwplaceholder="@lang('Enter text here')" class="header-section-p mb-7">Describe your company and services with few words and explain why you are the best choice.</p>

                    <div class="d-flex justify-content-center mt-4 mb-4">
                        <module type="contact_form" template="subscribe-1" />
                    </div>

                    <small>Your data will not be shared with third parties</small>
                    <br/> <br/><br/><br/>

                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}" style="max-width: 70%;" alt=""/>
                </div>
            </div>
        </div>
    </div>
</section>
