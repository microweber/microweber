<div class="left_col left">
  <div class="rounded_box">
    <div class="rounded_box_inner">
    <? 
	$shop_page = array();
				   $shop_page['content_layout_name'] = 'shop';
				  
				  $shop_page=get_pages($shop_page);
				  $shop_page = $shop_page[0];
	
	?>
    <ul class="category_list">
    <li><a href="<? print page_link( $shop_page['id']) ?>"><strong><? print $shop_page['content_title'] ?></strong></a></li>
    </ul>
     
    
      <microweber module="content/category_tree" ul_class="category_list"  for_page="<? print $shop_page['id'] ?>"  />
    </div>
    <div class="lt"></div>
    <div class="rt"></div>
    <div class="lb"></div>
    <div class="rb"></div>
  </div>
  <br />
  <a href="#"><img src="<? print TEMPLATE_URL ?>images/other/banner1.jpg" alt="" /></a> 
  
  

  
  
  <a href="https://www.facebook.com/pages/World-trade/83385238436" target="_blank"><img src="<? print TEMPLATE_URL ?>images/other/banner2.jpg" alt="" /></a> 
  
 <iframe src="http://www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FWorld-trade%2F83385238436&amp;width=262&amp;colorscheme=light&amp;show_faces=true&amp;border_color=%23fdf0fa&amp;stream=false&amp;header=false&amp;height=462" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:262px; height:462px;" allowTransparency="true"></iframe>
  </div>
