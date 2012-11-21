<?

if (!defined("MODULE_DB_TABLE_SHOP")) {
	define('MODULE_DB_TABLE_MENUS', TABLE_PREFIX . 'menus');
}

action_hook('mw_edit_page_admin', 'mw_print_admin_menu_selector');

function mw_print_admin_menu_selector($params = false) {
	//d($params);
	$add = '';
	if (isset($params['id'])) {
		$add = '&content_id=' . $params['id'];
	}
	print module('data-wrap=1&data-type=nav/edit_page_menus' . $add);
}

function get_menu($params = false) {

	$table = MODULE_DB_TABLE_MENUS;

	$params2 = array();
	if ($params == false) {
		$params = array();
	}
	if (is_string($params)) {
		$params = parse_str($params, $params2);
		$params = $params2;
	}

	//$table = MODULE_DB_TABLE_SHOP_ORDERS;
	$params['table'] = $table;
	$params['item_type'] = 'menu';
	return get($params);
}

function menu_tree($menu_id, $maxdepth = false) {

	static $passed_ids;
	if (!is_array($passed_ids)) {
		$passed_ids = array();
	}

	$params = array();
	$params['item_parent'] = $menu_id;
	// $params ['item_parent<>'] = $menu_id;
	$menu_id = intval($menu_id);
	$params_order = array();
	$params_order['position'] = 'ASC';

	$table_menus = MODULE_DB_TABLE_MENUS;

	$sql = "SELECT * from {$table_menus}
	where parent_id=$menu_id
	and item_type='menu_item'
	 
	order by position ASC ";
	//d($sql);
	$q = db_query($sql, __FUNCTION__ . crc32($sql), 'menus/' . $menu_id);

	// $data = $q;
	if (empty($q)) {
		return false;
	}
	$active_class = '';
	// $to_print = '<ul class="menu" id="menu_item_' .$menu_id . '">';
	$to_print = '<ul class="menu menu_' . $menu_id . '">';

	$cur_depth = 0;
	foreach ($q as $item) {
		$full_item = $item;

		$title = '';
		$url = '';

		if (intval($item['content_id']) > 0) {
			$cont = get_content_by_id($item['content_id']);
			if (isarr($cont)) {
				$title = $cont['title'];
				$url = content_link($cont['id']);
			}
		} else if (intval($item['taxonomy_id']) > 0) {
			$cont = get_category_by_id($item['taxonomy_id']);
			if (isarr($cont)) {
				$title = $cont['title'];
				$url = category_link($cont['id']);
			}
		} else {
			$title = $item['title'];
			$url = $item['url'];
		}

		if ($title != '') {
			//$full_item['the_url'] = page_link($full_item['content_id']);
			$to_print .= '<li class="menu_element ' . ' ' . $active_class . '" id="menu_item_' . $item['id'] . '">';
			$to_print .= '<a class="menu_element_link ' . ' ' . $active_class . '" href="' . $url . '">' . $title . '</a>';
			$to_print .= '</li>';
			if (in_array($item['id'], $passed_ids) == false) {

				if ($maxdepth == false) {
					$test1 = menu_tree($item['id']);
					if (strval($test1) != '') {
						$to_print .= strval($test1);
					}
				} else {

					if (($maxdepth != false) and ($cur_depth <= $maxdepth)) {
						$test1 = menu_tree($item['id']);
						if (strval($test1) != '') {
							$to_print .= strval($test1);
						}
					}
				}
			}

		}

		//	$passed_ids[] = $item['id'];
		// }
		// }
		$cur_depth++;
	}

	// print "[[ $time ]]seconds\n";
	$to_print .= '</ul>';

	return $to_print;
}
