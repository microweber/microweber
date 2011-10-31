<!-- sidebar box 2 -->
  <div class="content_right_box">
    <? $portfolio = get_page('portfolio'); ?>
    <h4>Recent ranking examples </h4>
    <a href="<? print page_link($portfolio['id']); ?>" class="nodec">Click here to see our portfolio</a> <br />
    <? //
	
	
	 $params = array(); 
 
 
  	$params['selected_categories'] = array($portfolio['content_subtype_value']); //if false will get the articles from the curent category. use 'all' to get all articles from evrywhere
  	$params['items_per_page'] = 5; //limits the results by paging
	 
//	$params['without_custom_fields'] = true; //if true it will get only basic posts info. Use this parameter for large queries
  

   $portfolios =get_posts($params) ;
	// p($portfolios);
	?>
    <ul>
      <? foreach($portfolios['posts'] as $p): ?>
      <li><a href="<? print post_link($p['id']); ?>" class="nodec14"><strong>
        <?  print $p['content_title']; ?>
        </strong></a></li>
      <? endforeach; ?>
    </ul>
  </div>
  <!-- /sidebar box 2 -->