<?php

/*

type: layout

name: Blog 2

position: 2

categories: Blog

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





<section class="section <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-blog-skin-3-{{ $params['id'] }}" rel="module">
    <module type="background" id="background-layout--{{ $params['id'] }}"/>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>
    <div class="container mw-layout-container">

        <!-- Jumbotron -->
        <div id="intro" class="my-5 p-5 text-center bg-light">
            <h1 class="mb-3 h2">Latest Posts</h1>
        </div>
        <!-- Jumbotron -->
        <module type="posts" template="skin-2"/>
    </div>
    <module type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom" />
</section>

