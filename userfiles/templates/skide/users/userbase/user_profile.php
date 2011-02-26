<? 
$dashboard_user = $author['id'];

?>
<div class="wrap">
  <div id="main_content">
    <?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>
    <?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_right.php') ?>
    <h2 class="blue-title-normal"><?php print $page['content_title'] ?></h2>
    <?php require (ACTIVE_TEMPLATE_DIR.'dashboard/index.php') ?>
    <div class="clear"></div>
    <!-- /#content -->
  </div>
  <!-- /#main_content -->
</div>
