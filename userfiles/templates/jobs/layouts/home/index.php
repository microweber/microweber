<?php

/*

type: layout

name: Home layout

description: Home site layout

*/

?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="banner" id="banner123">
  <div class="banner_content">
    <div class="banner_tit">MEDICAL CAREERS IN USA </div>
    <div class="banner_caption">POST YOUR OPPORTUNITY OR CV</div>
    <div class="joinfree_but"><a href="<? print page_link('register'); ?>"><img src="<? print TEMPLATE_URL ?>images/joinfree_but.jpg" alt="join free" /></a></div>
    <div class="login_but"><a href="<? print page_link('register'); ?>/view:login"><img src="<? print TEMPLATE_URL ?>images/login_but.jpg" alt="login" /></a></div>
    <div class="login_links"> <a href="<? print page_link('register'); ?>"> Free registration in 15 seconds</a><br />
      <a href="<? print page_link('register'); ?>/view:forgot-pass">Forgot my passwrod</a> </div>
  </div>
</div>
<div class="body_part">
  <div class="body_left">
  <div class="body_left_tit">Recent Job Listings</div>
  <div class="body_left_content">
    <? 
	$posts =  get_posts('items_per_page=5&page=3484');  
	
 
    $param2['page'] = 3481; 
  $param2['debug'] = false;
  $param2['limit'] = false;
  $param2['get_count'] = true;
	
	$posts_temp =  get_posts($param2);  
				 
				// echo count($posts);
				 
				?>


    <? include TEMPLATE_DIR. "layouts/jobs/jobs_block_home.php";


	?>

	<div class="pagination">
	<a href="#" id="prev">Prev</a>	
	<? 
	$tot_pages =count($posts_temp)/5;
	$tot_pages=$tot_pages+1;
	
	
	   $pages = paging_links($tot_pages,$posts_temp,1);
	 	paging('uls',$pages); ?>
		
		<a href="#" id="next">Next</a>
		

	
    <div class="page_number">
      
	 </div>
	 </div>
<div class="social_blk">
					<div class="icon_f"><a href="#"><img src="<? print TEMPLATE_URL ?>images/icon_f.png" alt="facebook" border="0" /></a></div>
					<a href="#" id="icon_f_text">become a fan on facebook </a>
					<div class="icon_f"><a href="#"><img src="<? print TEMPLATE_URL ?>images/icon_t.png" alt="facebook" width="36" height="36" border="0" /></a></div>
					<a  href="#" id="icon_t_text">Followus on twitter </a>
					<div class="icon_ln"><a href="#"><img src="<? print TEMPLATE_URL ?>images/icon_ln.png" alt="ln" border="0" /></a></div>
				</div>
	
	
  </div>
  </div>
  <div class="body_rt">
    <div class="searchbg">
      <div class="search_tit">Search for Jobs</div>
<!--      <input type="text" class="textbox" value="Keyword" />
      <select class="xdropbox" style="">
        <option>Select speciality</option>
		<? //category_tree('for_page=jobs'); ?>
      </select>

      <div class="c">&nbsp;</div>
 
      

      <input type="text" class="textbox" value="Location" />
      <select class="xdropbox" style="">
        <option>Pasted in last 7 days</option>
      </select>-->
      
      
          <form method="post" id="home_search_jobs" class="jobsform">
          <? include TEMPLATE_DIR. "layouts/jobs/search_fields.php"; ?> 

      
      <div class="c">&nbsp;</div>
      <input type="image" src="<? print TEMPLATE_URL ?>images/search_but.png" class="search_but" />

      </form>
      <a href="#" id="subscribe">Subscribe</a>
      <div class="seealljobs"><a href="#"><img src="<? print TEMPLATE_URL ?>images/seealljobs_but.png" alt="see all jobs" border="0" /></a></div>
    </div>
    <div class="features_comapnies_tit">Features Companies</div>
    <div class="left_logo"><img src="<? print TEMPLATE_URL ?>images/exemplar_logo.png" alt="exemplar logo" width="300" /></div>
    <div class="logo_box"><img src="<? print TEMPLATE_URL ?>images/medicaljobsite_logo.jpg" alt="medicl job site" width="297" height="75" /></div>
    <div class="logo_box"><img src="<? print TEMPLATE_URL ?>images/paragona_logo.jpg" alt="medicl job site" width="297" height="40" /></div>
    <div class="logo_box"><img src="<? print TEMPLATE_URL ?>images/id_medical_logo.jpg" alt="medicl job site" width="297" height="75" /></div>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
