<?php include (ACTIVE_TEMPLATE_DIR."offers_heading_nav.php"); ?>

<div class="holder">
  <div class="hleft">
    <div class="list-offers mega" style="padding-top: 0"> <span class="hr"></span>
      <?php if(count($active_categories) < 2) : ?>
      <?php include (ACTIVE_TEMPLATE_DIR."articles_cat_nav_center.php"); ?>
      <?php else : ?>
      <?php include (ACTIVE_TEMPLATE_DIR."articles_list_inner.php"); ?>
      <?php endif; ?>
      <div class="c"></div>
      <?php //include (ACTIVE_TEMPLATE_DIR."articles_paging_nojs.php"); ?>
    </div>
  </div>
  <!-- /#left -->
  <?php include (ACTIVE_TEMPLATE_DIR."articles_sidebar.php"); ?>
  <!-- /#right -->
  <div class="c"></div>
</div>
