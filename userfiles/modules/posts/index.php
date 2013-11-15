<?php
$curent_page = 1;

$post_params = $params;

if (isset($post_params['id'])) {
    $paging_param = 'curent_page' . crc32($post_params['id']);
    unset($post_params['id']);
} else {

}
$cat_from_url = url_param('category');
//$paging_param = 'curent_page';


if (isset($params['curent_page'])) {
    $curent_page = $params['curent_page'];
} else {
    $curent_page_from_url = url_param($paging_param);

    if ($curent_page_from_url != false) {
        $curent_page = $curent_page_from_url;
    }
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


if (!isset($config['template_file'])) {

//$config['template'] = get_option('data-template', $config['id']);
    //$config['template_file'] =
}


if (isset($params['data-paging-param'])) {

    $paging_param = $params['data-paging-param'];

}


$show_fields = false;
if (isset($post_params['data-show'])) {
    //  $show_fields = explode(',', $post_params['data-show']);

    $show_fields = $post_params['data-show'];
}
if (isset($post_params['show'])) {
    //  $show_fields = explode(',', $post_params['data-show']);

    $show_fields = $post_params['show'];
} else {

}
$show_fields1 = get_option('data-show', $params['id']);
if ($show_fields1 != false  and is_string($show_fields1)  and trim($show_fields1) != '') {
    $show_fields = $show_fields1;
}
if ($show_fields != false and is_string($show_fields)) {
    $show_fields = explode(',', $show_fields);
}

if (isset($post_params['limit'])) {
    $post_params['limit'] = $post_params['limit'];
}
if (isset($post_params['data-limit'])) {
    $post_params['limit'] = $post_params['data-limit'];
}


if (!isset($post_params['data-limit'])) {
    $lim = get_option('data-limit', $params['id']);
    if ($lim != false) {
        $post_params['limit'] = $lim;
    }
} else if (!isset($post_params['limit'])) {

}

$posts_parent_category = $posts_parent_category_cfg = get_option('data-category-id', $params['id']);
if($posts_parent_category == ''){
$posts_parent_category = false;	
}
$set_category_for_posts = false;

$lim = get_option('data-limit', $params['id']);
if ($lim != false) {
    $post_params['data-limit'] = $post_params['limit'] = $lim;
}
$cfg_page_id = get_option('data-page-id', $params['id']);
if ($cfg_page_id == false and isset($post_params['data-page-id'])) {
    $cfg_page_id = intval($post_params['data-page-id']);
} else if ($cfg_page_id == false and isset($post_params['content_id'])) {
    $cfg_page_id = intval($post_params['content_id']);
} else {
    // $cfg_page_id = get_option('data-page-id', $params['id']);

}


if ($posts_parent_category == false and isset($post_params['category_id'])) {
    $posts_parent_category = $post_params['category_id'];
}

if ($posts_parent_category == false and isset($post_params['related'])) {
    if (defined('CATEGORY_ID') and CATEGORY_ID > 0) {
        $posts_parent_category = CATEGORY_ID;
    }
}


if ($posts_parent_category == false and ($cfg_page_id == 'current_page')) {
    if (defined('PAGE_ID') and PAGE_ID > 0) {
        $cfg_page_id = PAGE_ID;
    }
}


if ($posts_parent_category_cfg == false) {

    if (defined('CATEGORY_ID') and CATEGORY_ID > 0) {
        $posts_parent_category = CATEGORY_ID;
    }

}
 
if($cfg_page_id == false and isset($post_params['related']) and $post_params['related'] != false and (!isset($post_params['parent']) or $post_params['parent'] == false)){
	 		
		if (defined('PAGE_ID') and PAGE_ID > 0) {
			$cfg_page_id = PAGE_ID;
		    $post_params['parent'] = $cfg_page_id;

		}
	 
}
if (!isset($params['global'])) {

if ($cfg_page_id != false and intval($cfg_page_id) > 0) {
    $sub_cats = array();
    $page_categories = false;
    if (intval($cfg_page_id) != 0 and $cat_from_url == false) {
        $str0 = 'table=categories&limit=1000&data_type=category&what=categories&' . 'parent_id=[int]0&rel_id=' . $cfg_page_id;
        $page_categories = get($str0);
        // d($page_categories);
        if (is_array($page_categories)) {
            foreach ($page_categories as $item_cat) {
                //d($item_cat);
                $sub_cats[] = $item_cat['id'];
                $more = get_category_children($item_cat['id']);
                if ($more != false and is_array($more)) {
                    foreach ($more as $item_more_subcat) {
                        $sub_cats[] = $item_more_subcat;
                    }
                }

            }
        }
		if(!empty($sub_cats)){
			//$post_params['category'] = $sub_cats;
		}
    }
	
	
	
    if ($posts_parent_category != false and intval($posts_parent_category) > 0 and $cat_from_url == false) {
        if ($page_categories != false and is_array($page_categories) and !empty($page_categories)) {
           // $sub_cats = array();
            foreach ($page_categories as $item_cat) {
                if (intval($item_cat['id']) == intval($posts_parent_category)) {
                    $sub_cats[] = $item_cat['id'];
                }
            }
        } elseif($posts_parent_category_cfg != false) {
             	  $post_params['category'] = $posts_parent_category_cfg;  
        }
	
	
        if (is_array($sub_cats) and !empty($sub_cats) and isset($post_params['related']) and $post_params['related'] != false) {
             $post_params['category'] = $sub_cats;
        } elseif($cfg_page_id != false) {
			$post_params['parent'] = $cfg_page_id;
		}
		
		
	


    } else {
        $post_params['parent'] = $cfg_page_id;
		
		if ((!isset($post_params['category']) or $post_params['category'] == false ) and $cat_from_url != false){
		  $post_params['category'] = $cat_from_url;
		}
	
    }


} elseif ($cat_from_url != false){
  $post_params['category'] = $cat_from_url;
  

} elseif ($posts_parent_category != false and intval($posts_parent_category) > 0 and ($cfg_page_id) == false) {
    $post_params['category'] = $posts_parent_category;

} elseif ($posts_parent_category_cfg != false and intval($posts_parent_category_cfg) > 0) {
    $post_params['category'] = $posts_parent_category_cfg;

}
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

$character_limit = 120;
$cfg_character_limit = get_option('data-character-limit', $params['id']);
if ($cfg_character_limit != false and trim($cfg_character_limit) != '') {
    $character_limit = intval($cfg_character_limit);
} else if (isset($params['description-length'])) {
    $character_limit = intval($params['description-length']);
}
$title_character_limit = 200;
$cfg_character_limit1 = get_option('data-title-limit', $params['id']);
if ($cfg_character_limit1 != false and trim($cfg_character_limit1) != '') {
    $title_character_limit = intval($cfg_character_limit1);
} else if (isset($params['title-length'])) {
    $title_character_limit = intval($params['title-length']);
}


if ($show_fields == false) {
//$show_fields = array('thumbnail', 'title', 'description', 'read_more');
}

if (is_array($show_fields)) {

    $show_fields = array_trim($show_fields);

}

if (isset($curent_page) and intval($curent_page) > 0) {
    $post_params['curent_page'] = intval($curent_page);

}

// $post_params['debug'] = 'posts';
if (!isset($post_params['content_type'])) {
    $post_params['content_type'] = 'post';
    // $post_params['subtype'] = 'post';

}

if ($post_params['content_type'] == 'product') {
    $post_params['subtype'] = 'product';
    $post_params['content_type'] = 'post';
} else {
    //$post_params['subtype'] = 'post';
    //$post_params['content_type'] = 'post';
}


if (isset($params['is_shop'])) {
    $post_params['subtype'] = 'product';
    unset($post_params['is_shop']);
} else {
    if (!isset($post_params['content_type'])) {
// $post_params['subtype'] = 'post';
    }
}
if (!isset($post_params['subtype']) and !isset($post_params['global'])) {
    $post_params['subtype'] = 'post';
}


if (!isset($params['order_by'])) {
    $post_params['orderby'] = 'position desc';
}


$schema_org_item_type = false;
$schema_org_item_type_tag = false;
//http://schema.org/CreativeWork
if (isset($post_params['content_type']) and $post_params['content_type'] == 'page') {
    $schema_org_item_type = 'WebPage';

} else if (isset($post_params['content_type']) and $post_params['content_type'] == 'post') {
    if (isset($post_params['subtype']) and $post_params['subtype'] != $post_params['content_type']) {
        $schema_org_item_type = $post_params['subtype'];

    } else {
        $schema_org_item_type = 'Article';
    }
}


if ($schema_org_item_type != false) {
    $schema_org_item_type = ucfirst($schema_org_item_type);
    $schema_org_item_type_tag = ' itemtype="http://schema.org/' . $schema_org_item_type . '" ';
    $schema_org_item_type_tag = 'http://schema.org/' . $schema_org_item_type;


}


$ord_by = get_option('data-order-by', $params['id']);
if ($ord_by != false and trim($ord_by) != '') {
    $post_params['orderby'] = $ord_by;
}

$date_format = get_option('date_format', 'website');
if ($date_format == false) {
    $date_format = "Y-m-d H:i:s";
}
if (isset($params['title'])) {

    unset($post_params['title']);
}

$post_params['is_active'] = 'y';
$post_params['is_deleted'] = 'n';



if (((!isset($post_params['parent']) and !isset($post_params['category']) or isset($post_params['category']) and empty($post_params['category']))and $cat_from_url != false and trim($cat_from_url) != '')) {
    $post_params['category'] = ($cat_from_url);
}
if (isset($params['content_type']) and $params['content_type'] == 'all') {
    unset($post_params['content_type']);
    unset($post_params['subtype']);

}

//


if (isset($params['search-parent'])) {
    $sub_content = get_content_children($params['search-parent']);
    if (!empty($sub_content)) {
        $post_params['ids'] = $sub_content;
        unset($post_params['parent']);
    }
}
if (isset($params['data-id'])) {
    unset($post_params['data-id']);
}
 

		
	if (isset($post_params['category']) and is_string($post_params['category'])) {	
		$sub_cats = array();
		$sub_cats[] = $post_params['category'];
		$more = get_category_children($post_params['category']);
		if ($more != false and is_array($more)) {
			foreach ($more as $item_more_subcat) {
			$sub_cats[] = $item_more_subcat;
			}
		}
		$post_params['category'] = $sub_cats;
		
	} else if(isset($post_params['category']) and is_array($post_params['category']) and empty($post_params['category']) and isset($post_params['related']) and $post_params['related'] != false) {	
	 if (defined('CATEGORY_ID') and CATEGORY_ID > 0) {
			 
			$post_params['category'] = CATEGORY_ID;

		}

	}





$content = get_content($post_params);
$data = array();

if (!empty($content)) {
// $data = $content;
//  if(!empty($show_fields)){

    foreach ($content as $item) {
        $iu = get_picture($item['id'], $for = 'post', $full = false);

        if ($iu != false) {
            $item['image'] = $iu;
        } else {
            $item['image'] = false;
        }
        $item['content'] = htmlspecialchars_decode($item['content']);
      

        if (isset($item['created_on']) and  trim($item['created_on']) != '') {
            $item['created_on'] = date($date_format, strtotime($item['created_on']));
        }

        if (isset($item['updated_on']) and  trim($item['updated_on']) != '') {
            $item['updated_on'] = date($date_format, strtotime($item['updated_on']));
        }

        $item['link'] = content_link($item['id']);


        if (!isset($item['description']) or $item['description'] == '') {
            if (isset($item['content']) and $item['content'] != '') {

                $item['description'] = character_limiter(strip_tags($item['content']), $character_limit);

            }
        } else {
            $item['description'] = character_limiter(strip_tags($item['description']), $character_limit);
        }


        if (isset($item['title']) and $item['title'] != '') {

            $item['title'] = character_limiter(($item['title']), $title_character_limit);

        }

        if (isset($post_params['subtype']) and $post_params['subtype'] == 'product') {
            $item['prices'] = get_custom_fields("field_type=price&for=content&for_id=" . $item['id']);

        } else {
            $item['prices'] = false;
        }


        $data[] = $item;
    }
// }
} else {
    if (isset($params['is_shop'])) {
        print lnotif('Your products module is empty');

    }  elseif (isset($params['global'])) {
        print lnotif('Your content module is empty');

    } else {
        print lnotif('Your posts module is empty');

    }
}


$post_params_paging = $post_params;
$post_params_paging['page_count'] = true;
//$post_params_paging['page_count'] = true;
//$post_params_paging['data-limit'] = $post_params_paging['limit'] = false;
$cfg_data_hide_paging = get_option('data-hide-paging', $params['id']);

if ($cfg_data_hide_paging === false) {
    if (isset($post_params['hide-paging'])) {
        $cfg_data_hide_paging = 'y';
        unset($post_params['hide-paging']);
    }
}

if ($cfg_data_hide_paging != 'y') {
    $pages_of_posts = get_content($post_params_paging);
    $pages_count = intval($pages_of_posts);
} else {
    $pages_count = 0;
}



	 


$paging_links = false;
if (intval($pages_count) > 1) {
    //$paging_links = mw('content')->paging_links(false, $pages_count, $paging_param, $keyword_param = 'keyword');

}

$read_more_text = get_option('data-read-more-text', $params['id']);


if (!isset($params['return'])) {

    $module_template = get_option('data-template', $params['id']);
    if ($module_template == false and isset($params['template'])) {
        $module_template = $params['template'];
    }


    if ($module_template != false) {
        if (strtolower($module_template) == 'none') {
            if (isset($params['template'])) {
                $module_template = $params['template'];
            }
        }
        $template_file = module_templates($config['module'], $module_template);

    } else {
        $template_file = module_templates($config['module'], 'default');

    }
    if ($template_file == false) {
        $template_file = module_templates($config['module'], 'default');
    }

    if (isset($template_file) and is_file($template_file) != false) {
        include($template_file);

        ?>
        <?php if (isset($params['ajax_paging'])): ?>
            <script type="text/javascript">


                $(document).ready(function () {

                    mw.$('#<?php print $params['id'] ?>').find('a[data-page-number]').unbind('click');
                    mw.$('#<?php print $params['id'] ?>').find('a[data-page-number]').click(function (e) {
                        var pn = $(this).attr('data-page-number');

                        mw.$('#<?php print $params['id'] ?>').attr('paging_param', 'curent_page');
                        mw.$('#<?php print $params['id'] ?>').attr('curent_page', pn)
                        mw.reload_module('#<?php print $params['id'] ?>');


                        return false;
                    });


                });


            </script>
        <?php endif; ?>
        <?php if (isset($params['is_shop'])): ?>
            <script type="text/javascript">
                mw.require("shop.js");
            </script>
        <?php endif; ?>
    <?php

    } else {

        print lnotif('No default template for ' . $config['module'] . ' is found');
    }
}
