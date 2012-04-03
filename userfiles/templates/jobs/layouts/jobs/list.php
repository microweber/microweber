
<div class="searchjobs_banner"><a href="#"><img src="<? print TEMPLATE_URL ?>images/Search_Jobs_banner.jpg" alt="banner" border="0"  /></a> </div>
<div class="searchjobs_tit">Search for jobs</div>
<div class="job_search_box">
  <div class="jobsearch_form_fileds">
    <form method="post">
    <table width="100" border="0" cellspacing="0" cellpadding="0">
      <tr valign="middle">
        <td><div class="jobsearch_form_fileds">
         <input type="hidden"   name="search" value="1" />
       
            <input type="text" class="jobsearch_textbox1"  name="keyword"   />
            <select class="jobsearch_drop" name="specialty">
              <option value="">Select Specialty</option>
            </select>
            <div class="jobsearch_drop_close"></div>
          </div></td>
        <td><div class="jobsearch_form_fileds">
      <input type="text" class="jobsearch_textbox1" name="location|state|zip"  />
      <select class="jobsearch_drop">
        <option>Posted - in last 7 days</option>
      </select>
      <div class="jobsearch_drop_close"></div>
    </div></td>
     <td>&nbsp; </td>
        <td> <div class="searchjob_but">
    <input type="image" src="<? print TEMPLATE_URL ?>images/Search_Jobs_but.jpg" />
  </div></td>
      </tr>
    </table>
      
    </form>
  </div>
 
</div>
<div class="searchjob_page_tit">Recent Job Listings</div>
<div class="body_part_inner">
  <div class="page_nav_sort">
    <div class="page_number">
      <table width="100%" border="0">
        <tr>
          <td>Pages:</td>
          <td><?   paging('uls'); ?></td>
        </tr>
      </table>
    </div>
  </div>
  <? foreach($posts as $post): ?>
  <div class="searched_job_blk">
    <div class="searched_job_tit"><a href="<? print post_link($post['id']) ?>"><? print $post['content_title'] ?></a></div>
    <div class="searched_job_desc"><? print $post['content_body_nohtml'] ?></div>
    <div class="searched_job_add_buts">
      <div class="searched_job_location"> Date Posted:&nbsp;&nbsp;&nbsp;<span class="blue"><? print $post['created_on'] ?></span><br />
        <?  $v = cf_val($post['id'], $field_name = 'Location') ;?>
        <? if($v != false): ?>
        Location:&nbsp;&nbsp;&nbsp;<span class="blue"><? print $v; ?> </span> <br />
        <? endif; ?>
        <?  $v = cf_val($post['id'], $field_name = 'sallary-range') ;?>
        <? if($v != false): ?>
        Salary:&nbsp;&nbsp;&nbsp;<span class="blue"><? print $v; ?></span> <br />
        <? endif; ?>
      </div>
      <div class="readmore_but"><a href="<? print post_link($post['id']) ?>"><img src="<? print TEMPLATE_URL ?>images/readmore_but.jpg" alt="read more" border="0" /></a></div>
      <div class="apply_but"><a href="<? print post_link($post['id']) ?>"><img src="<? print TEMPLATE_URL ?>images/apply_but.jpg" alt="apply" border="0" /></a></div>
    </div>
  </div>
  <? endforeach;  ?>
  <div class="pagination_searchjob">
    <div class="page_number">
      <table width="100%" border="0">
        <tr>
          <td>Pages:</td>
          <td><?   paging('uls'); ?></td>
        </tr>
      </table>
    </div>
    <!--<a href="#" id="prev">Prev</a>
    <div class="page_number"> <a href="#" class="current">1</a> <a href="#">2</a> <a href="#">3</a> <a href="#">4</a> <a href="#">5</a><a href="#" id="next">Next</a> </div>-->
  </div>
  <div class="feat_companies_tit">Featured Companies</div>
  <div class="logo_scroller">
    <div class="scrollingbuts">
      <div id="footer-partners">
        <div class="bx-wrapper" style=" position: relative; float:left; ">
          <div class="bx-window" style="position: relative; overflow: hidden;  ">
            <ul style="width: 999999px; position: relative; padding:0px; margin:0px;">
              <li style=" float: left; list-style: none outside none;"><img src="<? print TEMPLATE_URL ?>images/c_logo_30.jpg" height="104" /></li>
              <li class="pager" style=" float: left; list-style: none outside none;"><img src="<? print TEMPLATE_URL ?>images/c_logo_33.jpg" height="104" /> </li>
              <li class="pager" style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_36.jpg" /></li>
              <li class="pager" style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_43.jpg" height="104" /> </li>
              <li style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_46.jpg" /> </li>
              <li class="pager" style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_48.jpg" /> </li>
              <li class="pager" style="width: 125px; float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_54.jpg" /> </li>
              <li style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_57.jpg" /></li>
              <li class="pager" style=" float: left; list-style: none outside none;"><img src="<? print TEMPLATE_URL ?>images/c_logo_60.jpg" height="104" /> </li>
              <li class="pager" style=" float: left; list-style: none outside none;"> <img src="<? print TEMPLATE_URL ?>images/c_logo_66.jpg" height="104" /></li>
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
