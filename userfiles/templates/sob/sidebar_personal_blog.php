<div class="sidebar">
  <?php if(!empty( $author)): ?>
  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_widgets.php') ?>
  <?php endif; ?>
  <?php //require (ACTIVE_TEMPLATE_DIR.'sidebar_recent_posts.php') ?>
  
  <?php $use_master_categories_for_products = true;
   $use_master_categories_for_trainings = true;
   require (ACTIVE_TEMPLATE_DIR.'sidebar_products.php') ?>
  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_trainings.php') ?>
   
</div>
