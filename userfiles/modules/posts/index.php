<?
// d($params);

$post_params = $params;

if (isset($post_params['id'])) {
    $paging_param = 'curent_page' . crc32($post_params['id']);
    unset($post_params['id']);
} else {
    $paging_param = 'curent_page';
}

if (isset($post_params['paging_param'])) {
	$paging_param = $post_params['paging_param'];
}


if (isset($params['curent_page'])) {
	$curent_page = $params['curent_page'];
}

if (isset($post_params['data-page-number'])) {

    $post_params['curent_page'] = $post_params['data-page-number'];
    unset($post_params['data-page-number']);
}



if (isset($post_params['data-category-id'])) {

    $post_params['category'] = $post_params['data-category-id'];
    unset($post_params['data-category-id']);
}






if (isset($params['data-paging-param'])) {

    $paging_param = $params['data-paging-param'];

}





$show_fields = false;
if (isset($post_params['data-show'])) {
    //  $show_fields = explode(',', $post_params['data-show']);

    $show_fields = $post_params['data-show'];
} else {
    $show_fields = get_option('data-show', $params['id']);
}

if ($show_fields != false and is_string($show_fields)) {
    $show_fields = explode(',', $show_fields);
}





if (!isset($post_params['data-limit'])) {
    $post_params['limit'] = get_option('data-limit', $params['id']);
}
$cfg_page_id = false;
if (isset($post_params['data-page-id'])) {
     $cfg_page_id =   intval($post_params['data-page-id']);
} else {
    $cfg_page_id = get_option('data-page-id', $params['id']);

}

	if ($cfg_page_id != false and intval($cfg_page_id) > 0) {
		$sub_cats = array();
		
			$str0 = 'table=table_taxonomy&limit=1000&data_type=category&what=categories&' . 'parent_id=[int]0&to_table_id=' . $cfg_page_id;
		$page_categories = get($str0);
		//d($page_categories);
		if(isarr($page_categories)){
			foreach ($page_categories as $item_cat){
			$sub_cats[] = $item_cat['id'];
			$more =    get_category_children($item_cat['id']);
			if(isarr($more)){
				foreach ($more as $item_more_subcat){
					$sub_cats[] = $item_more_subcat;
				}
			}
		//	d($more);
			}
		}
		
				
						if(empty($sub_cats)){
						
						$par_page = get_content_by_id($cfg_page_id);
						if(isset($par_page['subtype']) and strval($par_page['subtype']) == 'dynamic' and isset($par_page['subtype_value']) and intval(trim($par_page['subtype_value'])) > 0){
					  $sub_cats = get_category_children($par_page['subtype_value']);
					  if(!empty($sub_cats)){
							$sub_cats = implode(',',$sub_cats);
							 
							$post_params['category'] = $par_page['subtype_value'].','.$sub_cats;
					 
						} else {
							$post_params['category'] = $par_page['subtype_value'];
						}
					  } 
					  	
						}
						
						
						
						
						 $post_params['parent'] = $cfg_page_id;	
						
					  
						 
					
		
		
	}  
	
	
	
	
	
$tn_size = array('150');

if (isset($post_params['data-thumbnail-size'])) {
    $temp = explode('x', strtolower($post_params['data-thumbnail-size']));
    if (!empty($temp)) {
        $tn_size = $temp;
    }
} else {
    $cfg_page_item = get_option('data-thumbnail-size', $params['id']);
    if ($cfg_page_item != false) {
        $temp = explode('x', strtolower($cfg_page_item));

        if (!empty($temp)) {
            $tn_size = $temp;
        }
    }
}





 


// $post_params['debug'] = 'posts';
$post_params['content_type'] = 'post';	
if(isset($params['is_shop'])){
	$post_params['subtype'] = 'product';
	unset($post_params['is_shop']);
} else {

}


$content   =$data = get_content($post_params);
?>
<?
$post_params_paging = $post_params;
//$post_params_paging['count'] = true;




$post_params_paging['page_count'] = true;
 $pages = get_content($post_params_paging);
 

$pages_count = intval($pages);
?>
<? if (intval($pages) > 1): ?>
<? $paging_links = paging_links(false, $pages_count, $paging_param, $keyword_param = 'keyword'); ?>
<? if (!empty($paging_links)): ?>

<div class="paging">
  <? foreach ($paging_links as $k => $v): ?>
  <span class="paging-item" data-page-number="<? print $k; ?>" ><a  data-page-number="<? print $k; ?>" data-paging-param="<? print $paging_param; ?>" href="<? print $v; ?>"  class="paging-link"><? print $k; ?></a></span>
  <? endforeach; ?>
</div>
<? endif; ?>
<? endif; ?>
<hr>
<div class="content-list">
  <? if (!empty($content)): ?>
  <? if ($show_fields == false): ?>
  <? $show_fields = array('thumbnail', 'title', 'description', 'read_more'); ?>
  <? endif; ?>
  <? foreach ($content as $item): ?>
  <div class="content-item" data-content-id="<? print ($item['id']) ?>">
    <? if (is_array($content) and !empty($content)): ?>
    <? foreach ($show_fields as $show_field): ?>
    <?
                        $show_field = trim($show_field);

 $fv = false;
                        switch ($show_field) {

                            case 'read_more':
                                $u = post_link($item['id']);
                                $fv = "<a href='{$u}' class='read_more'>Read more</a>";
                                break;

                            case 'thumbnail':
                               // if (isset($item[$show_field])) {
                                    $u = post_link($item['id']);
									 if (!isset($tn_size) or $tn_size == false) {
										 $tn_size = array();
									 }

                                    if (!isset($tn_size[0])) {
                                       $tn_size[0] = 180;
                                    }  
									
									
                                    if (!isset($tn_size[1])) {
                                       $tn_size[1] = 120;
                                    }  
									
									
									 $wstr = " width='{$tn_size[0]}' ";


                                    if (isset($tn_size[1])) {
                                        $hstr = " height='{$tn_size[1]}' ";
                                    }  

                                    //  d($hstr);
 $iu = get_picture($item['id'], $for = 'post', $full = false);
  
  
  
  
  
                                  //  $iu = $item[$show_field];
									if(trim($iu != '')){
										 $iu = thumbnail($iu,$tn_size[0],$tn_size[1] );
                                    $fv = $fv_i = "<img src='{$iu}' {$wstr} {$hstr} />";
									}
                                    // $fv = "<a href='{$u}' class='thumbnail'>{$fv_i}</a>";
                               // }

                                break;

                            default:
                                $fv = false;
                                if (isset($item[$show_field])) {
                                    $fv = $item[$show_field];
                                } else {

                                }





                                break;
                        }
                        ?>
    <? if ($fv != false and trim($fv) != ''): ?>
    <div class="post-field-<? print $show_field ?>"><? print $fv ?></div>
    <? endif; ?>
    <? endforeach; ?>
    <? // d($show_fields); ?>
    <? endif; ?>
  </div>
  <? endforeach; ?>
  <? else: ?>
  <div class="content-list-empty"> No posts </div>
  <? endif; ?>
</div>
