<?php

/*

type: layout

name: Feature 66 - Parallax

position: 66

categories: Features

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = '';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? '';
$layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>


<section class="section feature-66 mw-layout-parallax mw-layout-dark-background mh-350 d-flex align-items-center justify-content-center <?php print $layout_classes; ?> ">

    <module type="background" data-parallax="true" data-overlay-x="1" data-background-color="#00000060" data-background-image="{{ asset('templates/big/img/layouts/gallery-1-5.jpg') }}" id="background-layout--{{ $params['id'] }}" />

    <module height="100px" type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>

    <div class="mw-layout-container container no-element edit"
         field="layout-feature-skin-66-{{ $params['id'] }}" rel="module">
        <div class="row text-center justify-content-center align-items-center">
            <div class="col-lg-3">
                <h2>232</h2>
                <p>Clients</p>
            </div>
            <div class="col-lg-3">
                <h2>521</h2>
                <p>Projects</p>
            </div>
            <div class="col-lg-3">
                <h2>1453</h2>
                <p>Hour Of Support</p>
            </div>
            <div class="col-lg-3">
                <h2>32</h2>
                <p>Workers</p>
            </div>

        </div>

    </div>
    <module height="100px" type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>

</section>
