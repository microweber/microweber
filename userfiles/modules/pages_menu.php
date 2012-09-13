<?

/**
 * Print the site pages as tree
 *
 * @param string append_to_link 
 *        	You can pass any string to be appended to all pages urls
 * @param string link 
 *        	Replace the link href with your own. Ex: link="<? print site_url('page_id:{id}'); ?>"
 * @return string prints the site tree
 * @uses pages_tree($params);
 * @example  <module type="pages_menu" append_to_link="/editmode:y" />
 */
 
 
if(!isset($params['link'])){
	if(isset($params['append_to_link'])){
		$append_to_link = $params['append_to_link'];
	} else {
		$append_to_link = '';
	}
	 
	$params['link'] = '<a data-page-id="{id}" href="{link}'.$append_to_link.'">{title}</a>';
	
} else {
	
	$params['link'] = '<a data-page-id="{id}" href="'.$params['link'].'">{title}</a>';
}
 pages_tree($params) ?>


 