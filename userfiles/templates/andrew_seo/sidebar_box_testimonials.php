<div class="content_right_box2">
    <h4>What our clients say</h4>
    <br />
    <? $testimonials = get_page('testimonials'); ?>
    <? //
	
	
	 $params = array(); 
 
 
  	$params['selected_categories'] = array($testimonials['content_subtype_value']); //if false will get the articles from the curent category. use 'all' to get all articles from evrywhere
  	$params['items_per_page'] = 5; //limits the results by paging
	 
//	$params['without_custom_fields'] = true; //if true it will get only basic posts info. Use this parameter for large queries
  

   $testimonials =get_posts($params) ;
   shuffle( $testimonials['posts']);
   
	 //p($testimonials['posts']);
	?>
    <? $i=0; foreach($testimonials['posts'] as $p): ?>
    <? if($i == 0): ?>
    <blockquote><a href="<? print post_link($p['id']); ?>" class="txtlink">
      <?  print character_limiter($p['content_body_nohtml'], 150,'...'); ?>
      </a></blockquote>
    <p id="quoteauthor"><a href="<? print post_link($p['id']); ?>" class="nodec"><span class="bold">
      <?  print $p['content_title']; ?>
      </span></a></p>
    <? endif; ?>
    <? $i++; endforeach; ?>
  </div>