<?php

/*

type: layout

name: Blog 1

categories: blog

position: 1

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


<section class="section <?php print $layout_classes; ?> edit safe-mode nodrop" field="layout-blog-skin-1-<?php print $params['id'] ?>" rel="module">
    <div class="container">
        <module type="posts" template="skin-1" slides-md="2" slides-lg="3" slides-lg="3" adaptive_height="true" />
    </div>
</section>
