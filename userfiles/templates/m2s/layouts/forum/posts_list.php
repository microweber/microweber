
<div class="money_inner_left">
  <div class="choose_cat_box">
    <div class="sm_logo" style="float:left"><img src="<? print TEMPLATE_URL ?>images/sm_logo.jpg" alt="logo" /></div>
    <div class="choose_cat_lable">Choose category</div>
    <?
	  $get_categories_params = array(); 
    $get_categories_params['parent'] = $page['content_subtype_value']; //begin from this parent category

$cats =  get_categories($get_categories_params);
	//p($cats); 
	//print CATEGORY_ID;
 
	?>
    <form name="jumpopt" id="jumpopt">
      <select class="cat_dropbox" name="myjumpbox"  OnChange="location.href=jumpopt.myjumpbox.options[selectedIndex].value">
        <? foreach($cats as $cat): ?>
        <option <? if(CATEGORY_ID == $cat['id']):  ?>  selected="selected" <? endif; ?> value="<? print get_category_url($cat['id']); ?>"  ><? print $cat['taxonomy_value'] ?></option>
        <? endforeach; ?>
      </select>
    </form>
    <!--<div class="drop_but"></div>-->
  </div>
  <? foreach($posts as $post): ?>
  <?
  $post_image_data = get_picture($post['id'], $for = 'post'); 
  	$thumb = get_media_thumbnail($post_image_data['id'], 192);
  
  ?>
  <div class="each_cat_box">
    <div class="each_cat_box_left">
      <div class="cat_logo" style="background-image:url('<? print $thumb ?>')">
        <!--
      <img src="<? print TEMPLATE_URL ?>images/cat_logo_1.jpg" alt="logo" />
      -->
        <!--  <img src="<? print $thumb ?>" alt="logo" />-->
      </div>
      <div class="cat_url"><a href="http://www.companysite.com" target="_blank">www.companysite.com</a></div>
    </div>
    <div class="each_cat_box_rt">
      <div class="page_tit"><? print $post['content_title'] ?></div>
      <div class="cat_text2">
	  
	  
	  <? print   character_limiter( $post['content_description'], 250 , "...") ?> </div>
      <div class="seethis_offer_but"><a href="<? print post_link($post['id']) ?>"><img src="<? print TEMPLATE_URL ?>images/seethis_offer_but.jpg" alt="offer" border="0" /></a></div>
    </div>
  </div>
  <? endforeach; ?>
  
  <div class="pagination"> <a href="#" id="prev">Prev</a>
    <div class="page_number"> <a href="#" class="current">1</a> <a href="#">2</a> <a href="#">3</a> <a href="#">4</a> <a href="#" id="next">Next</a> </div>
  </div>
</div>
<div class="money_inner_rt">


 <? if($mid_posts != false): ?>
  <div class="shopping_box">


<mw module="content/category_tree" include_first="0" content_parent="<? print $page['content_subtype_value'] ?>" />

        
<!--          <div class="all_cat_but"><img src="<? print TEMPLATE_URL ?>images/all_cat_but.jpg" alt="View all categories" /></div>
-->        </div>
  <? endif; ?>





        
        

  <div class="money_sponsored_text">&nbsp;: </div>
  <div class="sony_img"><img src="<? print TEMPLATE_URL ?>images/sony_img.jpg" alt="sony" border="0" usemap="#Map" />
    <map name="Map" id="Map">
      <area shape="rect" coords="17,209,143,229" href="#" />
    </map>
  </div>
  <div class="last10_box">
    <div class="hot_offer_subhead">Last 10 Hot Offers</div>
    <?
	$params = array();
	$params['selected_categories'] = array($page['content_subtype_value']); //if false will get the articles from the curent category. use 'all' to get all articles from evrywhere
  	$params['items_per_page'] = 10; //limits the results by paging
	$params['curent_page'] = 1; //curent result page

 $posts_sidebar = get_posts($params);
 
 
// p( $posts_sidebar);

	?>
    <? $i=1; foreach($posts_sidebar['posts'] as $post_sb): ?>
    <div class="hot_offer_rec">
      <div class="hot_offer_number"><? print $i ?></div>
      <div class="hot_offer_text"><a href="<? print post_link($post_sb['id']) ?>"><? print $post_sb['content_title'] ?></a></div>
    </div>
    <? $i++; endforeach; ?>
  </div>
  <div class="last10_box">
    <div class="subscribe_tit">Subscribe for best offers </div>
    <div class="subscribebg">
      <input type="text" class="subscribe_mail" />
      <div class="subscribe_but">
        <input type="image" src="<? print TEMPLATE_URL ?>images/subscribe_but.jpg" />
      </div>
    </div>
    <div class="money_sponsored_text2"> &nbsp; </div>
    <div class="sponsor"><img src="<? print TEMPLATE_URL ?>images/sponsor_84.jpg" alt="sponsor" /></div>
    <div class="sponsor"><img src="<? print TEMPLATE_URL ?>images/sponsor_86.jpg" alt="sponsor" /></div>
    <div class="sponsor"><img src="<? print TEMPLATE_URL ?>images/sponsor_90.jpg" alt="sponsor" /></div>
    <div class="sponsor"><img src="<? print TEMPLATE_URL ?>images/sponsor_92.jpg" alt="sponsor" /></div>
  </div>
</div>
