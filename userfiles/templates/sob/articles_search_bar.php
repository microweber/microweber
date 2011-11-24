 <?php dbg(__FILE__); ?>
<?php $view = $this->core_model->getParamFromURL ( 'view' ); ?>
<?php $view = ($this->core_model->getParamFromURL ( 'view' )) ? "/view:".$this->core_model->getParamFromURL ( 'view' ) : false;  ?>
<?php $voted = ($this->core_model->getParamFromURL ( 'voted' )) ? "/voted:".$this->core_model->getParamFromURL ( 'voted' ) : false;  ?>
<?php $keyword = ($this->core_model->getParamFromURL ( 'keyword' )) ? "/keyword:".$this->core_model->getParamFromURL ( 'keyword' ) : false;  ?>
<?php if(($controller_url)!= false){
	$this_page_url = $controller_url;	 
} else {
	$this_page_url = $this->content_model->getContentURLById($page['id']);	 
}

if(!empty($active_categories)){
$force_seach_url =$this->taxonomy_model->getUrlForIdAndCache($active_categories[0]); 	
}


if(($controller_url)!= false){
	$force_seach_url = $controller_url;
}
 


$params = array();
$params['view'] = 'inherit';
$params['commented'] == 'inherit';
$params['voted'] = 'inherit';
$params['curent_page'] = 1;
//$params['keyword'] = false; 
 

$temp = $this->core_model->urlConstruct($base_url = $this_page_url,$params );
?>


<?php /*
<form id="posts-search"  method="post" action="<?php print  $temp;   ?>">
  <label>Search Site</label>
  <input  id="posts-search-input" type="text" value="<?php if($search_for_keyword): ?><?php print $search_for_keyword ?><?php else: ?><?php endif; ?>"  name="search_by_keyword"   />
  <input type="submit" value="" id="posts-search-submit"   />
</form>
 */ ?>



<?php $time_span = array(); 
$time_span[] = '1 hour';
$time_span[] = '1 day';
$time_span[] = '7 days'; 
$time_span[] = '30 days'; 
$time_span[] = '6 months';
$time_span[] = '1 year'; 
 $time_span = array_reverse($time_span);

//exit;


?>


<div class="entepreneur-top-bar">

<div class="view-by">
<div class="item">
<label>Most voted:</label>
<span class="linput">
<select class='select_redirect'>

 
 <?php $params = array();
$params['view'] = 'inherit';
unset($params['commented'] );
unset($params['voted'] );
$params['keyword'] =  'inherit';
unset($params['ord'] );
 $params['curent_page'] = 1;
$temp = $this->core_model->urlConstruct($base_url = $this_page_url,$params );
?><option value="<?php print  $temp ?>"  >Any</option>


  <?php foreach($time_span as $item):
  $params = array();
  $params['view'] = 'inherit';
  //$params['commented'] == false;
  $params['voted'] = $item;
  $params['keyword'] =  'inherit';
  $params['ord'] =  'voted';
  $params['curent_page'] = 1;

  $temp = $this->core_model->urlConstruct($base_url = $this_page_url,$params );
  ?><option value="<?php print  $temp ?>"  <?php if ($this->core_model->getParamFromURL ( 'voted' ) == $item) { print ' selected="selected" ' ; }  ?> ><?php print $item ?></option>
  <?php endforeach; ?>

</select>
</span>
</div>
<div class="item">
 <label>Most commented:</label>
 <span class="linput">
 <select class='select_redirect'>
 
 
 <?php $params = array();
$params['view'] = 'inherit';
unset($params['commented'] );
unset($params['voted'] );
$params['keyword'] =  'inherit';
unset($params['ord'] );
 $params['curent_page'] = 1;
$temp = $this->core_model->urlConstruct($base_url = $this_page_url,$params );
?><option value="<?php print  $temp ?>"  >Any</option>
 
 
<?php foreach($time_span as $item): 
$params = array();
$params['view'] = 'inherit';
$params['commented'] =  $item;
unset($params['voted'] );
$params['keyword'] =  'inherit';
$params['ord'] =  'commented'; 
$params['curent_page'] = 1;
$temp = $this->core_model->urlConstruct($base_url = $this_page_url,$params );
?><option value="<?php print  $temp ?>"  <?php if ($this->core_model->getParamFromURL ( 'commented' ) == $item) { print ' selected="selected" ' ; }  ?> ><?php print $item ?></option>
<?php endforeach; ?>
</select>
</span>
</div>
</div>

<div class="order-search">
   <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_search.php') ?>
</div>

</div>
 <?php dbg(__FILE__,1); ?>

