{{--
type: layout
name: Grid 5
position: 5
categories: Grids
--}}


<section class="section {{ $layout_classes }} ">
    <module type="background" id="background-layout--{{ $params['id'] }}" />
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container safe-mode edit" field="layout-grids-skin-5-{{ $params['id'] }}" rel="module">
        <div class="row">
            <div class="col-sm mb-2 cloneable element safe-mode layouts-grids-background">
                <div class="w-100 cube-wrapper">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-8.jpg') }}" alt="">
                </div>
            </div>
            <div class="col-sm mb-2 cloneable element safe-mode layouts-grids-background">
                <div class="w-100 cube-wrapper">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-9.jpg') }}" alt="">
                </div>
            </div>
            <div class="col-sm mb-2 cloneable element safe-mode layouts-grids-background">
                <div class="w-100 cube-wrapper">
                    <img loading="lazy" src="{{ asset('templates/big/img/layouts/gallery-1-13.jpg') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
