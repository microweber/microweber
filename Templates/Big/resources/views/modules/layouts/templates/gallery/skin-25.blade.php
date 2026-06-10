{{--
type: layout
name: Gallery 25
position: 25
categories: Gallery
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';
    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }}">

    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top" />
    <div class="mw-layout-container no-element container-fluid edit" field="layout-gallery-skin-25-{{ $params['id'] }}" rel="module">
        <div class="col-md-10 mx-md-auto mb-5 cloneable px-0">
            <div class="mx-auto mb-5 d-lg-flex justify-content-between">
                <div class="col-lg-6">
                    <h1 class="mb-3" style="font-size: 64px;">My Best Captured</h1>
                </div>

                <div class="col-lg-4 pt-lg-4">
                    <p>I wanted to tell a story on the street. And while often in magazines the act of walking is portrayed in a stereotyped and frankly boring way, this is where looking to the future is for me.</p>
                </div>
            </div>

            <module type="pictures" template="skin-15"/>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>
