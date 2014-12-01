<?php

/*

  type: layout
  content_type: static
  name: Home 2
  position: 11
  description: Home layout 2

*/

?>
<?php include THIS_TEMPLATE_DIR. "header.php"; ?>

<module type="magicslider" id="homeslider" rel="content">

<div class="mw-wrapper" style="padding: 40px 0px;">
  <div class="edit" rel="home_2" field="content">
        <?php include THIS_TEMPLATE_DIR . 'modules/layout/picture_with_categories.php'; ?>
        <p class="element"><br></p>
        <?php include THIS_TEMPLATE_DIR . 'modules/layout/picture_with_categories.php'; ?>
  </div>
</div>
<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>
