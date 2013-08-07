<div id="community-header">
 <?php
$current_category = false;
   $url_cat = url_param('category'); ?>
<?php if($url_cat == false): ?>
<?php 

$current_categorys = get_categories_for_content(CONTENT_ID);
if(!empty($current_categorys)){
	$current_category = array_shift($current_categorys);
}
 
?>
<?php else: ?>
<?php  $current_category = get_category_by_id($url_cat);  ?>
<?php endif; ?>
<?php if(user_id() == false){   ?>

      <div class="container">
          <a class="pull-left lite-btn" href="login">Login to add New Post</a>
      </div>


<?php  } else {  ?>


      <div class="container">


 <div class="pull-left">
           
          <a  href="<?php print page_link(); ?>" ><?php print $page['title'] ; ?></a>


<?php if($current_category != false): ?>
	
	&nbsp; > &nbsp;
          <a  href="<?php print category_link($current_category['id']); ?>" ><?php print $current_category['title'] ; ?></a>

<?php endif; ?>

          </div>
 

          <div class="pull-right">
          	<h5 class="pull-left">Welcome, <?php print user_name(); ?></h5>
          <a class="pull-left iicon" href="<?php print mw_site_url(); ?>profile" ><i class="icon-pencil"></i> <small>Edit profile</small></a>
          


          <a class="pull-left lite-btn" href="<?php print page_link(PAGE_ID); ?>?new-topic<?php if($current_category != false): ?>&category=<?php print $current_category['id']; ?><?php endif; ?>">New Post</a>


          </div>
      </div>


<?php } ?>

    </div>