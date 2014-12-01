<?php

/*

  type: layout
  content_type: static
  name: Home
  position: 2
  description: Home layout

*/

?>
<?php include TEMPLATE_DIR. "header.php"; ?>

<div class="container">
  <div class="box-container">
  <div class="edit" field="content" rel="content">

  

     <?php /* <module type="categories" template="pictured" />  */ ?>


   <module type="posts" template="grid" hide-paging="true" data-show="thumbnail,title" />


  </div>
  </div>
</div>
<?php include TEMPLATE_DIR. "footer.php"; ?>
