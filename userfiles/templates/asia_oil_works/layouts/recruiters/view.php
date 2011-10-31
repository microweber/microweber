<? $id = url_param('id'); 


$user = get_user($id);



?>
<? $posts_by_user =  get_posts("items_per_page=1000&created_by=".$id);
$posts = $posts_by_user['posts'];
//p($posts);
?>
<?  $cfs = get_custom_fields_for_user($id);
 
 

 ?>
<? if(!$cfs["picture"]["custom_field_value"]) {
		  
		 $cfs["picture"]["custom_field_value"] =   TEMPLATE_URL.'no_logo.gif';
	  }?>

<div class="pad2 ishr"> <a href="#" class="print">Print</a>
  <h2><? print user_name($id); ?></h2>
</div>
<div class="pad2 relative"> <span class="jobcount"><strong><? print count($posts); ?> job</strong> offers in this site</span>
  <center>
    <img src="<? print site_url('phpthumb/phpThumb.php') ?>?src=<? print $cfs["picture"]["custom_field_value"]  ?>&h=200&w=200" alt="" />
  </center>
  <br />
  <br />
  <br />
  <h2>Company Information</h2>
  <div class="richtext">
    <? // p($cfs ); ?>
    <? print $cfs["custom_field_info"]["custom_field_value"]  ?> </div>
  <br />
  <!--<a href="htpp://www.lukoil.com" class="blue" target="_blank" rel="nofollow">www.lukoil.com</a>-->
  <br />
  <br />
  <h2>Jobs by <? print user_name($id); ?></h2>
  <? include(TEMPLATE_DIR.'layouts/search_jobs/jobs_list.php'); ?>
</div>
