<?php

/*

type: layout

name: Titles 1

position: 1

categories: Titles

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


<section class="section <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-titles-skin-1-{{ $params['id'] }}" rel="module">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="container mw-layout-container">
        <div class="row text-center mb-5 nodrop">
            <div class="col-lg-10 mx-auto allow-drop">
                <h1 class="mb-3"><?php print content_title(); ?></h1>
                <p class="lead edit" field="layout-titles-skin-1-description-{{ $params['id'] }}" rel="module">Discover our latest updates, articles and offerings.</p>
            </div>
        </div>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</section>
