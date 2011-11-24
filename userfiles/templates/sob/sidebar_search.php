 <?php dbg(__FILE__); ?><?php if(($controller_url)!= false){
	$this_page_url = $controller_url;	 
} else {
	$this_page_url = $this->content_model->getContentURLById($page['id']);	 
}

if((!empty($active_categories)) and $active_categories != "false"){
//	var_dump($active_categories);
$seach_url =$this->taxonomy_model->getUrlForIdAndCache($active_categories[0]); 	
} else {
	$seach_url =site_url('browse');
}

if(($controller_url)!= false){
	 
	$seach_url =$controller_url;
}

$params = array();
$params['view'] = 'list';
$params['type'] = 'inherit';
$params['commented'] =  'inherit';
$params['voted'] = 'inherit';

$params['ord'] =  'inherit';

$temp = $this->core_model->urlConstruct($base_url = $seach_url,$params );


$kw = $this->core_model->getParamFromURL ( 'keyword' );
if(!$kw){
	$kw = 'Search for content';
} 
if($_POST ['search_by_keyword']){
$kw = $_POST ['search_by_keyword'];
} 
?>
<?php // print $temp  ?>
<form class="dform" method="post" action="<?php print $temp  ?>">
<?php if(($controller_url)!= false) : ?>
<input name="search_by_keyword_auto_append_params" type="hidden" value="0" />

<?php endif; ?>

    <input type="text" class="type-text" name="search_by_keyword" value="<?php print $kw ?>" onfocus="this.value=='Search for content'?this.value='':''" onblur="this.value==''?this.value='Search for content':''" />
    <input value="" type="submit" class="type-submit"  />
  </form> <?php dbg(__FILE__,1); ?>