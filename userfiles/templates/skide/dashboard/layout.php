<?php include ACTIVE_TEMPLATE_DIR."header.php" ?>
<!-- /home_head -->

<div class="wrap">
  <div id="main_content">
    <?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>
    <? if(url_param('ns') == false): ?>
    <?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_right.php') ?>
    <? endif; ?>
    <h2 class="blue-title-normal"><?php print ucwords($page['content_title']); ?></h2>
    <?    ?>
    
    {content} 
    
    
    <div class="clear"></div>
    <!-- /#content -->
  </div>
  <!-- /#main_content -->
</div>
<?php include ACTIVE_TEMPLATE_DIR."footer.php" ?>
