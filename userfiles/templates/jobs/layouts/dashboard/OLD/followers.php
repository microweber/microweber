<?php dbg(__FILE__); ?>
<!--Dashboard circle of influence page-->
<?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>

<div id="profile-main">
 <?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_top_nav.php') ?>

<?php if (!empty($dashboard_followers)) { ?>
	
	<?php foreach ($dashboard_followers as $friend) { ?>
		<?php require (ACTIVE_TEMPLATE_DIR.'dashboard/followers_item.php') ?>
	<?php }?>
	
    
    
    
    
    
    <?php require (ACTIVE_TEMPLATE_DIR.'articles_paging.php') ?>
    
    
<?php } else { ?>
<div class="noposts">
   You don't have any followers.
</div>
<?php }?>

</div><!-- /#profile-main -->
<?php dbg(__FILE__, 1); ?>