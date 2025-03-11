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
<module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-top" />


<section class="section <?php print $layout_classes; ?> edit" field="layout-blog-skin-3-{{ $params['id'] }}" rel="module">

    <div class="container mw-layout-container">

      <div class="row">
          <h1 class="text-center my-3">Blog posts</h1>

          <module type="posts" />
      </div>

    </div>
</section>

<module type="spacer" id="spacer-layout--{{ $params['id'] ?? '' }}-bottom" />
