
<div class="searchjobs_banner"><a href="#"><img src="<? print TEMPLATE_URL ?>images/Search_Jobs_banner.jpg" alt="banner" border="0"  /></a> </div>
<div class="searchjobs_tit">Search for jobs</div>
<div class="job_search_box">
  <div class="jobsearch_form_fileds" style="float:left">
    <form method="post" id="inner_search_jobs" class="jobsform">

           <? include TEMPLATE_DIR. "layouts/jobs/search_fields.php"; ?>


          
            
            

      <div class="searchjob_but">
              <input type="image" src="<? print TEMPLATE_URL ?>images/Search_Jobs_but.jpg" />
            </div>

    </form>
  </div>
</div>
<div class="searchjob_page_tit">Recent Job Listings</div>
<div class="body_part_inner">
  <div class="page_nav_sort">
    <?php
   $param2['page'] = 3481; 
  //$param2['debug'] = false; 
  $param2['limit'] = false;
  $param2['get_count'] = true;
	
	$posts_temp =  get_posts($param2);  
  
  ?>
    <div class="pagination"> <a href="#" id="prev">Prev</a>
      <? 
	$tot_pages =count($posts_temp)/5;
	$tot_pages=$tot_pages+1;
	
	
	   $pages = paging_links($tot_pages,$posts_temp,1);
	 	paging('uls',$pages); ?>
      <a href="#" id="next">Next</a>
      <div class="page_number"> </div>
    </div>
  </div>
  <? include TEMPLATE_DIR. "layouts/jobs/jobs_block.php"; ?>
  <div class="pagination"> <a href="#" id="prev">Prev</a>
    <? 
	$tot_pages =count($posts_temp)/5;
	$tot_pages=$tot_pages+1;
	
	
	   $pages = paging_links($tot_pages,$posts_temp,1);
	 	paging('uls',$pages); ?>
    <a href="#" id="next">Next</a>
    <div class="page_number"> </div>
  </div>
  <div class="feat_companies_tit">Featured Companies</div>
  <div class="logo_scroller">
    <div class="scrollingbuts">
      <div id="footer-partners">
        <div class="bx-wrapper" style=" position: relative; float:left; ">
          <div class="bx-window" style="position: relative; overflow: hidden;  ">
            <ul style="width: 999999px; position: relative; padding:0px; margin:0px;">
              <li style=" float: left; list-style: none outside none;"><img src="<? print TEMPLATE_URL ?>images/c_logo_30.jpg" height="95" /></li>
              <li class="pager" style=" float: left; list-style: none outside none;"><img src="<? print TEMPLATE_URL ?>images/c_logo_33.jpg" height="95" /> </li>
              <li class="pager" style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_36.jpg" /></li>
              <li class="pager" style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_43.jpg" height="95" /> </li>
              <li style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_46.jpg" /> </li>
              <li class="pager" style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_48.jpg" /> </li>
              <li class="pager" style="width: 125px; float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_54.jpg" /> </li>
              <li style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_57.jpg" /></li>
              <li class="pager" style=" float: left; list-style: none outside none;"><img src="<? print TEMPLATE_URL ?>images/c_logo_60.jpg" height="95" /> </li>
              <li class="pager" style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_66.jpg" height="95" /></li>
              <li class="pager" style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_69.jpg" /></li>
              <li style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_72.jpg" /></li>
              <li class="pager" style="width: 133px; float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_78.jpg" /> </li>
              <li class="pager" style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_81.jpg" /> </li>
              <li class="pager" style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_84.jpg" /> </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="social_blk_inner">
  <div class="icon_f"><a href="#"><img src="<? print TEMPLATE_URL ?>images/icon_f.png" alt="facebook" border="0" /></a></div>
  <a href="#" id="icon_f_text">become a fan on facebook </a>
  <div class="icon_f"><a href="#"><img src="<? print TEMPLATE_URL ?>images/icon_t.png" alt="facebook" width="36" height="36" border="0" /></a></div>
  <a  href="#" id="icon_t_text">Followus on twitter </a>
  <div class="icon_ln"><a href="#"><img src="<? print TEMPLATE_URL ?>images/icon_ln.png" alt="ln" border="0" /></a></div>
</div>
