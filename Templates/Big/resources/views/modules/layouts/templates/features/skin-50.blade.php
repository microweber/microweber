{{--
 type: layout
 name: Feature 50
 position: 50
 categories: Features
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<style>
    .feature-50 ul {
        list-style: none;
        padding-left: 10px;
    }

    .feature-50 li {
        position: relative;
    }

    .feature-50 li:before {
        content: '';
        position: absolute;
        top: 12px;
        left: -15px;
        width: 24px;
        height: 24px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' height='24' viewBox='0 -960 960 960' width='24'%3E%3Cpath d='M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z' fill='%230494d7' /%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-size: contain;
    }

    .feature-50 .img-holder .image-1 {
        width: 80% !important;
        height: 80% !important;
        position: absolute;
        top: 0;
        left: 0;
    }

    .feature-50 .img-holder {
        padding: 0 70px;
        position: relative;
        min-height: 500px;
        margin-bottom: 20px;
    }

    .feature-50 .img-holder img {
        object-fit: cover;
        object-position: center;
    }

    .feature-50 .img-holder .image-2 {
        width: 70% !important;
        height: 70% !important;
        position: absolute;
        border: 15px solid #fff;
        bottom: 0;
        right: 0;
    }
</style>

<section class="section feature-50 {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="mw-layout-container no-element container edit" field="layout-feature-skin-50-{{ $params['id'] }}" rel="module">
        <div class="row col-12 col-xxl-8 mx-auto">
            <div class="col-lg-6">
                <div class="img-holder h-100 w-100 background-image-holder">
                    <img class="image-1" loading="lazy" alt="" src="{{ asset('templates/big/img/layouts/gallery-1-2.jpg') }}">
                    <img class="image-2" loading="lazy" alt="" src="{{ asset('templates/big/img/layouts/gallery-1-7.jpg') }}">
                </div>
            </div>

            <div class="col-lg-6 ps-md-5 ps-3">
                <h4 style="font-weight: bold;" data-mwplaceholder="{{ _e('Enter title here') }}" class="mb-2">Useful links</h4>
                <ul class="ul safe-mode nodrop">
                    <li class="cloneable element safe-element">
                        <a href="" target="_blank">Your useful link title here</a>
                    </li>
                    <li class="cloneable element safe-element">
                        <a href="" target="_blank">Your useful link title here</a>
                    </li>
                    <li class="cloneable element safe-element">
                        <a href="" target="_blank">Your useful link title here</a>
                    </li>
                    <li class="cloneable element safe-element">
                        <a href="" target="_blank">Your useful link title here</a>
                    </li>
                    <li class="cloneable element safe-element">
                        <a href="" target="_blank">Your useful link title here</a>
                    </li>
                    <li class="cloneable element safe-element">
                        <a href="" target="_blank">Your useful link title here</a>
                    </li>
                    <li class="cloneable element safe-element">
                        <a href="" target="_blank">Your useful link title here</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>
