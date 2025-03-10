<?php

/*

type: layout

name: Blog 3

position: 3

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


<section class="section <?php print $layout_classes; ?> edit" field="layout-blog-skin-3-{{ $params['id'] }}" rel="module">
    <div class="container">

        <h2>Blog posts</h2>

        <module type="posts" />

    </div>
</section>
