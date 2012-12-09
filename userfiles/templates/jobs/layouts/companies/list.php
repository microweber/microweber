<div class="body_part_inner">
  <form class="search_part" method="post" action="<? print page_link() ?>">
    <input type="hidden" name="search" value="1"  />
    <div class="sort_people">Speciality:</div>
    <? 
	 
	  $speciality = url_param('speciality');
	
	 $roles = get_option('user_roles');
   $roles = csv2array($roles);
   //p($roles);
   
   if($speciality != false){
	  $speciality =  strtolower (urldecode($speciality)); 
	   
   } 
   
   
   
     ?>
    <select class="comapnies_search" name="speciality">
      <option value="">Select speciality</option>
      <? foreach($roles as $role) : ?>
      <option <? if((trim(strtolower($role))) ==  (trim($speciality))): ?> selected="selected" <? endif; ?> value="<? print strtolower($role); ?>"><? print $role; ?></option>
      <? endforeach ?>
    </select>
    
    <!--    <select class="comapnies_search" name="role" style="width:230px;">
      <option  value="job_seeker">Job seeker</option>
      <option  value="company">company</option>
    </select>-->
    <div class="company_search_hover" style="margin-left:-21px;"></div>
    <div class="by_keyword">By Keyword</div>
    <input type="text" class="companies_search_textbox" name="keyword" />
    <div class="companies_search_but">
      <input type="image" src="<? print TEMPLATE_URL ?>images/comapnies_search_but.jpg" />
    </div>
  </form>
  <?
   //p($speciality);
  
   ?>
  <? 
  
  $param = array();
  // $param['role'] = 'company';
  $kw = url_param('keyword');
    // $param['debug'] = true;  
	// $param['no_cache'] = true; 
  if( $kw != false){
	 $param['keyword'] = $kw; 
  }
  
    $curent_page = url_param('curent_page');
  if( $curent_page != false){
	 $param['curent_page'] = $curent_page; 
  } else {
	 $param['curent_page'] = 1;  
  }
  
    $role = url_param('role');
	
	if(strstr(url(), 'job-seekers')){
		  $role = 'job_seeker';
	} else {
		$role = 'company';
	}
	
 $param['role'] = $role; 
 
 
 
  
     
  if( $speciality != false){
	 $param['custom_field_speciality'] = $speciality; 
  } else {
	 
  }
  
  $param['debug'] = false;
   $param['no_cache'] = true;
  
  $param['items_per_page'] = 30; 
  
  $param2 = $param;
  $param2['debug'] = false; 
  $param2['limit'] = true;
  $param2['get_count'] = true;

    //p($param2); 
  
    $uc = get_users($param2);
 
   $u = get_users($param);
  //
  
  $pages = paging_links($uc,30);
 // paging('uls', $pages);
//  p($pa); 
   ?>
  <? 
  $i_count=0;
  foreach($u as $usr): $i_count= $i_count+1; if($i_count==4) { $i_count=1;}  ?>
  <div class="logo_box_<?php echo $i_count;?>"><a class="company_logo" href="<? print page_link(); ?>/view:profile/username:<? print $usr['username'] ?>" style="background-image:url('<? print user_thumbnail('id='.$usr['id'].'&size='. 200); ?>')"> </a>
    <div class="company_name"><strong>Company Name:</strong> <? print user_name($usr['id']); ?></div>
  </div>
  <? endforeach; ?>
  <?php echo "Current Page". url_param('curent_page');?>
  <div class="pagination"> <a href="#" id="prev">Prev</a>
    <? 
	
	$tot_pages =$uc/30;
	$tot_pages=$tot_pages+1;
	
	
	   $pages = paging_links($tot_pages,$u,1);
	 	paging('uls',$pages); ?>
    <a href="#" id="next">Next</a>
    <div class="page_number"> </div>
  </div>
</div>
