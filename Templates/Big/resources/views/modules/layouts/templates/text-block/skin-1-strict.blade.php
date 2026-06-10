{{-- 
type: layout

name: Strict Text block 1

position: 1

categories: Strict Text block
--}}

@php
    $classes['padding_top'] = $classes['padding_top'] ?? '';
    $classes['padding_bottom'] = $classes['padding_bottom'] ?? '';

    $layout_classes = $layout_classes ?? '';
    $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
@endphp

<section class="section {{ $layout_classes }} edit" field="layout-text-block-skin-1-{{ $params['id'] }}" rel="module">
    <div class="container">
        <div class="row text-center">
            <div class="col-12 col-lg-10 col-lg-8 mx-auto mw-dropable">
                <div class="mw-richtext">
                    <p>
                        Like rock stars, asteroids have been given their fair share of urban myth and lore. Many have attributed the extinction of the dinosaurs to the impact of a huge asteroid on the earth. Like rock stars, asteroids have been given their fair share of urban myth and lore. Many have attributed the extinction of the dinosaurs to the impact of a huge asteroid on the earth. Like rock stars, asteroids have been given their fair share of urban myth and lore. Many have attributed the extinction of the dinosaurs to the impact of a huge asteroid on the earth.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
