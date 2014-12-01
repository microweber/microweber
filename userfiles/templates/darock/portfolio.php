<?php

/*

  type: layout
  content_type: dynamic
  name: Portfolio
  position: 2
  description: Portfolio layout

*/

?>
<?php include TEMPLATE_DIR. "header.php"; ?>

<div class="container">
  <div class="edit" field="content" rel="content">

      <?php if(CATEGORY_ID ==0 ){ ?>

      <module type="categories" template="pictured" />

      <?php } else { ?>

      <module type="posts" template="grid" data-show="thumbnail,title" />


     <?php } ?>

  </div>
</div>
<?php include TEMPLATE_DIR. "footer.php"; ?>
