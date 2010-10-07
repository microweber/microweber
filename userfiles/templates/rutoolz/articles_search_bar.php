<?  $view = $this->core_model->getParamFromURL ( 'view' ); ?>
<? $view = ($this->core_model->getParamFromURL ( 'view' )) ? "/view:".$this->core_model->getParamFromURL ( 'view' ) : false;  ?>
<? $voted = ($this->core_model->getParamFromURL ( 'voted' )) ? "/voted:".$this->core_model->getParamFromURL ( 'voted' ) : false;  ?>
<? $keyword = ($this->core_model->getParamFromURL ( 'keyword' )) ? "/keyword:".$this->core_model->getParamFromURL ( 'keyword' ) : false;  ?>
<? 
if(($controller_url)!= false){
	$this_page_url = $controller_url;	 
} else {
	$this_page_url = $this->content_model->getContentURLById($page['id']);	 
}




$params = array();
$params['view'] = 'inherit';
$params['commented'] == 'inherit';
$params['voted'] = 'inherit';
$params['curent_page'] = 1;
//$params['keyword'] = false; 
 

$temp = $this->core_model->urlConstruct($base_url = $this_page_url,$params );
?>


<form id="posts-search"  method="post" action="<?  print  $temp;   ?>">
  <label>Search Site</label>
  <input  id="posts-search-input" type="text" value="<? if($search_for_keyword): ?><? print $search_for_keyword ?><? else: ?><? endif; ?>"  name="search_by_keyword"   />
  <input type="submit" value="search" id="posts-search-submit"   />
</form>
<br />
<br />

<div style="display:none;">
<?  
$time_span = array(); 
$time_span[] = '1 hour'; 
$time_span[] = '24 hours'; 
$time_span[] = '1 day'; 
$time_span[] = '7 days'; 
$time_span[] = '30 days'; 
$time_span[] = '6 months'; 
$time_span[] = '1 year'; 
$time_span = array(); 
?>
<? foreach($time_span as $item): 
$params = array();
$params['view'] = 'inherit';
//$params['commented'] == false;
$params['voted'] = $item;
$params['keyword'] =  'inherit'; 
$params['ord'] =  'voted'; 
$params['curent_page'] = 1;

$temp = $this->core_model->urlConstruct($base_url = $this_page_url,$params );
?><a href="<?  print  $temp ?>"  <? if ($this->core_model->getParamFromURL ( 'voted' ) == $item) { print ' class="active bold" ' ; }  ?> ><? print $item ?></a>
<? endforeach; ?>
<? foreach($time_span as $item): 
$params = array();
$params['view'] = 'inherit';
$params['commented'] =  $item;
unset($params['voted'] );
$params['keyword'] =  'inherit';
$params['ord'] =  'commented'; 
$params['curent_page'] = 1;
$temp = $this->core_model->urlConstruct($base_url = $this_page_url,$params );
?><a href="<?  print  $temp ?>"  <? if ($this->core_model->getParamFromURL ( 'commented' ) == $item) { print ' class="active bold" ' ; }  ?> ><? print $item ?></a>
<? endforeach; ?>
</div>