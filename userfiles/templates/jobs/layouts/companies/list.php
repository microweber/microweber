 


<div class="body_part_inner">
  <form class="search_part" method="post" action="<? print page_link() ?>">
    <input type="hidden" name="search" value="1"  />
    <div class="sort_people">Role:</div>
    <select class="comapnies_search" name="role">
    <option  value="job_seeker">Job seeker</option>
        <option  value="company">company</option>

    </select>
    <div class="company_search_hover"></div>
    <div class="by_keyword">By Keyword</div>
    <input type="text" class="companies_search_textbox" name="keyword" />
    <div class="companies_search_but">
      <input type="image" src="<? print TEMPLATE_URL ?>images/comapnies_search_but.jpg" />
    </div>
  </form>
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
  if( $role != false){
	 $param['role'] = $role; 
  } else {
	  
  }
  
  
  
  
  
  $param['items_per_page'] = 30; 
  
  $param2 = $param;
  $param2['debug'] = false; 
  $param2['limit'] = false;
  $param2['get_count'] = true;
    
  
    $uc = get_users($param2);
 
   $u = get_users($param);
  // p($param); 
  
  $pages = paging_prepare($uc,30);
 // paging('uls', $pages);
//  p($pa); 
   ?>
   
   <h2><? print $uc ?> users found</h2>
   
   
  <? foreach($u as $usr): ?>
  <div class="logo_box_1"> <a class="company_logo" href="<? print page_link(); ?>/view:profile/username:<? print $usr['username'] ?>" style="background-image:url('<? print user_picture($usr['id'], 200); ?>')"> </a>
    <div class="company_name"><strong>Company Name:</strong> <? print user_name($usr['id']); ?></div>
  </div>
  <? endforeach; ?>
  <!-- <div class="logo_box_1">
    <div class="company_logo"><img src="<? print TEMPLATE_URL ?>images/c_logo_36.jpg" width="277" height="93" /></div>
    <div class="company_name"><strong>Company Name:</strong> Id Medical</div>
  </div>
  <div class="logo_box_2">
    <div class="company_logo"><img src="<? print TEMPLATE_URL ?>images/c_logo_33.jpg" width="275" height="106" /></div>
    <div class="company_name"><strong>Company Name:</strong>Exemplar Health Resources<br />
    </div>
  </div>
  <div class="logo_box_3">
    <div class="company_logo"><img src="<? print TEMPLATE_URL ?>images/c_logo_30.jpg" width="250" height="129" /></div>
    <div class="company_name"><strong>Company Name:</strong>Carlin Medical Extrusions<br />
    </div>
  </div>
  <div class="logo_box_1">
    <div class="company_logo"><img src="<? print TEMPLATE_URL ?>images/c_logo_46.jpg" width="272" height="106" /></div>
    <div class="company_name"><strong>Company Name:</strong> Noris Medical</div>
  </div>
  <div class="logo_box_2">
    <div class="company_logo"><img src="<? print TEMPLATE_URL ?>images/c_logo_48.jpg" width="274" height="95" /></div>
    <div class="company_name"><strong>Company Name:</strong>Drager Medical<br />
    </div>
  </div>
  <div class="logo_box_3">
    <div class="company_logo"><img src="<? print TEMPLATE_URL ?>images/c_logo_43.jpg" width="167" height="117" /></div>
    <div class="company_name"><strong>Company Name:</strong>Medicross Medical Center<br />
    </div>
  </div>
  <div class="logo_box_1">
    <div class="company_logo"><img src="<? print TEMPLATE_URL ?>images/c_logo_57.jpg" width="286" height="87" /></div>
    <div class="company_name"><strong>Company Name:</strong> SOC Medical</div>
  </div>
  <div class="logo_box_2">
    <div class="company_logo"><img src="<? print TEMPLATE_URL ?>images/c_logo_60.jpg" width="272" height="79" /></div>
    <div class="company_name"><strong>Company Name:</strong>Grundfos<br />
    </div>
  </div>
  <div class="logo_box_3">
    <div class="company_logo"><img src="<? print TEMPLATE_URL ?>images/c_logo_54.jpg" width="239" height="95" /></div>
    <div class="company_name"><strong>Company Name:</strong>Amil<br />
    </div>
  </div>
  <div class="logo_box_1">
    <div class="company_logo"><img src="<? print TEMPLATE_URL ?>images/c_logo_72.jpg" width="288" height="92" /></div>
    <div class="company_name"><strong>Company Name:</strong>Altoona Regional Health System<br />
    </div>
  </div>
  <div class="logo_box_2">
    <div class="company_logo"><img src="<? print TEMPLATE_URL ?>images/c_logo_66.jpg" width="255" height="117" /></div>
    <div class="company_name"><strong>Company Name:</strong>Pharmaton Kiddi<br />
    </div>
  </div>
  <div class="logo_box_3">
    <div class="company_logo"><img src="<? print TEMPLATE_URL ?>images/c_logo_69.jpg" width="255" height="94" /></div>
    <div class="company_name"><strong>Company Name:</strong>Traumeel<br />
    </div>
  </div>
  <div class="logo_box_1">
    <div class="company_logo"><img src="<? print TEMPLATE_URL ?>images/c_logo_84.jpg" width="255" height="96" /></div>
    <div class="company_name"><strong>Company Name:</strong> Cystic Fibrosis Foundation</div>
  </div>
  <div class="logo_box_2">
    <div class="company_logo"><img src="<? print TEMPLATE_URL ?>images/c_logo_81.jpg" width="212" height="118" /></div>
    <div class="company_name"><strong>Company Name:</strong>Panorama Dental Group S. A. </div>
  </div>
  <div class="logo_box_3">
    <div class="company_logo"><img src="<? print TEMPLATE_URL ?>images/c_logo_78.jpg" width="122" height="120" /></div>
    <div class="company_name"><strong>Company Name:</strong>Ratownictwo Medyczne<br />
    </div>
  </div>-->
  <div class="pagination"> 
  
  <?  paging('uls', $pages); ?>
  
  <a href="#" id="prev">Prev</a>
    <div class="page_number">
  
    
    
     <a href="#" class="current">1</a> <a href="#">2</a> <a href="#">3</a> <a href="#">4</a> <a href="#">5</a><a href="#" id="next">Next</a> </div>
  </div>
</div>
