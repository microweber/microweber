<? 
 



		if($params['for_url']){
			
			
			$history_to_get = array ();
		$history_to_get ['table'] = 'edit';
		$history_to_get ['id'] = ( parse_url(strtolower($params['for_url']), PHP_URL_PATH) );
		$history_to_get ['value'] = $params;
		$history_to_get ['field'] = 'html_content';
		
 
			
			
			$history_files = get_history_files($history_to_get);
		 
		} else {
		
 
$the_dir = normalize_path ( TEMPLATE_DIR . 'blocks/' );
 $id = $params['id'];
 $page_id = $params['page_id'];
 $rel = $params['rel'];
 $field = $params['field'];
 
 if($rel == 'global'){
	 $page_id = false;
 }
 
  if($rel == 'page'){
	 $ref_page = $_SERVER ['HTTP_REFERER'];
				$ref_page = $_SERVER ['HTTP_REFERER'] . '/json:true';
				$ref_page = file_get_contents ( $ref_page );
				if ($ref_page != '') {
					$save_global = false;
					$ref_page = json_decode ( $ref_page );
					$page_id = $ref_page->page->id;
					 
				}
 }
 
 if($rel == 'post'){
	 $ref_page = $_SERVER ['HTTP_REFERER'];
				$ref_page = $_SERVER ['HTTP_REFERER'] . '/json:true';
				$ref_page = file_get_contents ( $ref_page );
				if ($ref_page != '') {
					$save_global = false;
					$ref_page = json_decode ( $ref_page );
					$page_id = $ref_page->post->id;
					 
				}
 }
 
 if ($page_id == true) {
			$the_dir = TEMPLATE_DIR . 'blocks/' . $page_id;
			$the_dir = normalize_path ( $the_dir );
			$try_file = $the_dir . $id . '.php';
			if (is_file ( $try_file )) {
			} else {
					$the_dir = normalize_path ( TEMPLATE_DIR . 'blocks/' ); 
			}
		} else {
			$the_dir = normalize_path ( TEMPLATE_DIR . 'blocks/' );
		}
		
		
		if($params['tag'] != 'edit'){
 
$history_dir = APPPATH . '/history/blocks/' . $id . '/';
			$history_dir = normalize_path ( $history_dir );
			$history_dir = reduce_double_slashes($history_dir);
			$history_files = array();
					foreach (glob($history_dir."*.php") as $filename) {
						$history_files [] = $filename;
					}
			
		} else {
			
			if(intval($page_id) == 0){
		//	$page_id = 'global';	
			}
			
			$history_dir = APPPATH . '/history/blocks/' . $id . '/';
			$history_dir = normalize_path ( $history_dir );
			$history_dir = reduce_double_slashes($history_dir);
			$hdata= array();
			if($rel != 'global'){
			$hdata['table'] = 'table_content';
			}
			$hdata['id'] = $page_id;
			
			
			$hdata['field'] = $field;
			
			$history_files = get_instance()->core_model->getHistoryFiles($hdata);
			// p($params);
 
		}
			//p($history_dir);
			
			
}			
			?>

<!--      <a class="mw_history_prev " href="javascript:mw_click_on_history_prev()"><</a>
<a class="mw_history_next " href="javascript:mw_click_on_history_next()">></a>    
            -->

<? print count($history_files);?>

history files <br />
<? // p($history_files); ?>
<ul id="mw_history_files">
  <? 		foreach ($history_files as $filename) : ?>
  <li rel="<? print base64_encode($filename) ?>">
    <? //$mtime= filemtime($filename ); ?>
    <?
	if (file_exists($filename)) {
   $mtime=  date ("F d Y H:i:s.", filemtime($filename));
}
	
	 
	//$content_of_file = file_get_contents($filename);	?>
    <a href="javascript: mw.history.load('<? print base64_encode($filename) ?>')">
    <? $fn1= rtrim(basename($filename ), '.php'); ?>
    <?  print $fn1  ?>
    (<? print ago($mtime, $granularity = 1); ?>) </a> </li>
  <? 		endforeach; ?>
</ul>
