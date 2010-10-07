<?php
/*
type: layout
name: users layout
description: users layout




*/

?>
 
<?php include ACTIVE_TEMPLATE_DIR."header.php" ?>
    <div id="home_head" style="height: auto">
      <div id="in-banner" class="about-baner">
        <div id="banner-bar">
          <h2 class="white-title"><?php print $page['content_title'] ?></h2>
        </div>
      </div>
      <!-- /in-banner -->
    </div>
    <!-- /home_head -->
    <?php if($no_breadcrumb_navigation == false): ?>
     <?php print $this->content_model->getBreadcrumbsByURLAndPrintThem(); ?>
     <div style="height: 40px;"><!--  --></div>
     <?php endif; ?>
      
      <div id="main">
        <h2 class="blue-title-normal"><?php print $page['content_title'] ?></h2>
         {content}  
         
      </div>
      <!-- /main -->
      <?php //include(ACTIVE_TEMPLATE_DIR.'affiliates_sidebar.php') ;  ?>
      <!-- /#sidebar -->
      <div class="clear"></div>
    <!-- /#content -->
    <?php include ACTIVE_TEMPLATE_DIR."footer.php" ?>