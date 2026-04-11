<?php

/*

type: layout

name: Text block 1

position: 1

categories: Text block

*/

?>

<?php
if (!$classes['padding_top']) {
    $classes['padding_top'] = '';
}
if (!$classes['padding_bottom']) {
    $classes['padding_bottom'] = '';
}

$layout_classes = $layout_classes ?? ''; $layout_classes .= ' ' . $classes['padding_top'] . ' ' . $classes['padding_bottom'] . ' ';
?>


<section class="section <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-text-block-skin-1-{{ $params['id'] }}" rel="module">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="container mw-layout-container">
        <div class="row text-center">
            <div class="col-12 col-lg-10 col-lg-8 mx-auto">
                <h5 data-mwplaceholder="Enter title here">Pictures In The Sky</h5>
                <p data-mwplaceholder="Enter text here">The $79 iWork '08 appears to be a good deal for anyone needing an affordable office suite for the Mac. Apple has finally added a spreadsheet application. At first glance, Numbers is an elegant no-brainer for anyone migrating from Microsoft Excel.</p>
                <br><br><br>
            </div>
        </div>

        <div></div>

        <module type="testimonials" id="{{ $params['id'] }}-testimonials" template="skin-10" project_name="Testimonials 1"/>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>

</section>
