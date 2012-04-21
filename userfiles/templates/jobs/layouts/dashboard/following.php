<?php dbg(__FILE__); ?>
<!--Dashboard circle of influence page-->
<?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_sidebar.php') ?>
 <?php $the_action =  $this->core_model->getParamFromURL ( 'action' ); ?>
<div id="profile-main">
 <?php require (ACTIVE_TEMPLATE_DIR.'dashboard/dashboard_top_nav.php') ?>
 <div class="c" style="padding-bottom: 20px">&nbsp;</div>
<?php if (!empty($dashboard_following)) { ?>
<?php foreach ($dashboard_following as $friend) { ?>
		<?php require (ACTIVE_TEMPLATE_DIR.'dashboard/followers_item.php') ?>
	<?php }?>

    
    
    
    
    
    <?php require (ACTIVE_TEMPLATE_DIR.'articles_paging.php') ?>
<?php } else { ?>



<?php if($the_action == 'following'): ?>
<div class="noposts">
    You don't follow anyone.
</div>
<?php endif;  ?>
<?php if($the_action == 'circle-of-influence'): ?>
<div class="noposts">
    No people in your circle of influence.
</div>
<?php endif;  ?>
<?php if($the_action == 'followers'): ?>
<div class="noposts">
   You don't have any followers.
</div>
<?php endif;  ?>

<?php }?>

</div><!-- /#profile-main -->
<?php dbg(__FILE__, 1); ?>