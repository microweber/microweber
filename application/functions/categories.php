<?php

if (!defined("MW_DB_TABLE_TAXONOMY")) {
	define('MW_DB_TABLE_TAXONOMY', MW_TABLE_PREFIX . 'categories');
}

if (!defined("MW_DB_TABLE_TAXONOMY_ITEMS")) {
	define('MW_DB_TABLE_TAXONOMY_ITEMS', MW_TABLE_PREFIX . 'categories_items');
}

action_hook('mw_db_init_default', 'mw_db_init_categories_table');

function mw_db_init_categories_table() {
	$function_cache_id = false;

	$args = func_get_args();

	foreach ($args as $k => $v) {

		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);
	}

	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);

	$cache_content = cache_get_content($function_cache_id, 'db');

	if (($cache_content) != false) {

		return $cache_content;
	}

	$table_name = MW_DB_TABLE_TAXONOMY;

	$fields_to_add = array();

	$fields_to_add[] = array('updated_on', 'datetime default NULL');
	$fields_to_add[] = array('created_on', 'datetime default NULL');
	$fields_to_add[] = array('created_by', 'int(11) default NULL');
	$fields_to_add[] = array('edited_by', 'int(11) default NULL');
	$fields_to_add[] = array('data_type', 'TEXT default NULL');
	$fields_to_add[] = array('title', 'longtext default NULL');
	$fields_to_add[] = array('parent_id', 'int(11) default NULL');
	$fields_to_add[] = array('description', 'TEXT default NULL');
	$fields_to_add[] = array('content', 'TEXT default NULL');
	$fields_to_add[] = array('content_type', 'TEXT default NULL');
	$fields_to_add[] = array('rel', 'TEXT default NULL');

	$fields_to_add[] = array('rel_id', 'int(11) default NULL');

	$fields_to_add[] = array('position', 'int(11) default NULL');
	$fields_to_add[] = array('is_deleted', "char(1) default 'n'");
	$fields_to_add[] = array('users_can_create_subcategories', "char(1) default 'n'");
	$fields_to_add[] = array('users_can_create_content', "char(1) default 'n'");
	$fields_to_add[] = array('users_can_create_content_allowed_usergroups', 'TEXT default NULL');

	$fields_to_add[] = array('categories_content_type', 'TEXT default NULL');
	$fields_to_add[] = array('categories_silo_keywords', 'TEXT default NULL');

	set_db_table($table_name, $fields_to_add);

	db_add_table_index('rel', $table_name, array('rel(55)'));
	db_add_table_index('rel_id', $table_name, array('rel_id'));
	db_add_table_index('parent_id', $table_name, array('parent_id'));

	$table_name = MW_DB_TABLE_TAXONOMY_ITEMS;

	$fields_to_add = array();
	$fields_to_add[] = array('parent_id', 'int(11) default NULL');
	$fields_to_add[] = array('rel', 'TEXT default NULL');

	$fields_to_add[] = array('rel_id', 'int(11) default NULL');
	$fields_to_add[] = array('content_type', 'TEXT default NULL');
	$fields_to_add[] = array('data_type', 'TEXT default NULL');

	set_db_table($table_name, $fields_to_add);

	//db_add_table_index('rel', $table_name, array('rel(55)'));
	db_add_table_index('rel_id', $table_name, array('rel_id'));
	db_add_table_index('parent_id', $table_name, array('parent_id'));

	cache_save(true, $function_cache_id, $cache_group = 'db');
	// $fields = (array_change_key_case ( $fields, CASE_LOWER ));
	return true;

	//print '<li'.$cls.'><a href="'.admin_url().'view:settings">newsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl eter</a></li>';
}

/**
 * category_tree
 *
 * @desc prints category_tree of UL and LI
 * @access      public
 * @category    categories
 * @author      Microweber
 * @param $params = array();
 * @param  $params['parent'] = false; //parent id
 * @param  $params['link'] = false; // the link on for the <a href
 * @param  $params['active_ids'] = array(); //ids of active categories
 * @param  $params['active_code'] = false; //inserts this code for the active ids's
 * @param  $params['remove_ids'] = array(); //remove those caregory ids
 * @param   $params['ul_class_name'] = false; //class name for the ul
 * @param  $params['include_first'] = false; //if true it will include the main parent category
 * @param  $params['content_type'] = false; //if this is set it will include only categories from desired type
 * @param  $params['add_ids'] = array(); //if you send array of ids it will add them to the category
 * @param  $params['orderby'] = array(); //you can order by such array $params['orderby'] = array('created_on','asc');
 * @param  $params['content_type'] = false; //if this is set it will include only categories from desired type
 * @param  $params['list_tag'] = 'select';
 * @param  $params['list_item_tag'] = "option";
 *

 */
function category_tree($params = false) {

	$function_cache_id = false;
	$args = func_get_args();
	foreach ($args as $k => $v) {
		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);
	}
	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);

	$cache_group = 'categories/global';
	$cache_content = cache_get_content($function_cache_id, $cache_group);
	// $cache_content = false;
	//if (!isset($_GET['debug'])) {
	if (($cache_content) != false) {
		print $cache_content;
		return;
		//  return $cache_content;
	}
	//}
	$p2 = array();
	// d($params);
	if (!is_array($params)) {
		if (is_string($params)) {
			parse_str($params, $p2);
			$params = $p2;
		}
	}
	if (isset($params['parent'])) {
		$parent = ($params['parent']);
	} else if (isset($params['subtype_value'])) {
		$parent = ($params['subtype_value']);
	} else {
		$parent = 0;
	}

	$link = isset($params['link']) ? $params['link'] : false;

	if ($link == false) {
		$link = "<a href='{categories_url}' data-category-id='{id}'  class='{active_code} {nest_level}'  >{title}</a>";
	}
	
	
	

	$active_ids = isset($params['active_ids']) ? $params['active_ids'] : array(CATEGORY_ID);
	if (isset($params['active_code'])) {
		$active_code = $params['active_code'];
	} else {
		$active_code = " active ";
	}

	if (isset($params['remove_ids'])) {
		$remove_ids = $params['remove_ids'];
	} else {
		$remove_ids = false;
	}

	if (isset($params['removed_ids_code'])) {
		$removed_ids_code = $params['removed_ids_code'];
	} else {
		$removed_ids_code = false;
	}
	$ul_class_name = '';
	if (isset($params['class'])) {
		$ul_class_name = $params['class'];
	}
	if (isset($params['ul_class'])) {

		$ul_class_name = $params['ul_class'];
	}

	if (isset($params['ul_class_name'])) {

		$ul_class_name = $params['ul_class_name'];
	}

	if (isset($params['include_first'])) {
		$include_first = $params['include_first'];
	} else {
		$include_first = false;
	}

	if (isset($params['content_type'])) {
		$content_type = $params['content_type'];
	} else {
		$content_type = false;
	}

	if (isset($params['add_ids'])) {
		$add_ids = $params['add_ids'];
	} else {
		$add_ids = false;
	}

	if (isset($params['orderby'])) {
		$orderby = $params['orderby'];
	} else {
		$orderby = false;
	}

	$table = MW_TABLE_PREFIX . 'categories';
	if (isset($params['content_id'])) {
		$params['for_page'] = $params['content_id'];

	}

	if (isset($params['for_page']) and $params['for_page'] != false) {
		$page = get_content_by_id($params['for_page']);
		//d($page);
		$parent = $page['subtype_value'];
	}
	if (isset($params['subtype_value']) and $params['subtype_value'] != false) {
		$parent = $params['subtype_value'];
	}

	if (isset($params['parent']) and $params['parent'] != false) {
		//	$parent = $params['parent'];
	}

	$skip123 = false;
	$fors = array();

	if (!isset($params['for'])) {
		$params['for'] = 'content';
	}
	if (!isset($params['content_id']) and isset($params['for']) and $params['for'] != false) {

		$table_assoc_name = db_get_assoc_table_name($params['for']);
		$skip123 = true;

		$str0 = 'orderby=position asc&table=' . $table . '&limit=1000&data_type=category&what=categories&' . 'parent_id=0&rel=' . $table_assoc_name;
		$fors = get($str0);

	}

	if (!isset($params['content_id']) and isset($params['try_rel_id']) and intval($params['try_rel_id']) != 0) {
		$skip123 = true;

		$str1 = 'orderby=position asc&table=' . $table . '&limit=1000&parent_id=0&rel_id=' . $params['try_rel_id'];
		$fors1 = get($str1);
		if (isarr($fors1)) {
			$fors = array_merge($fors, $fors1);

		}

	}

	if (isset($params['not_for_page']) and $params['not_for_page'] != false) {
		$page = get_page($params['not_for_page']);
		$remove_ids = array($page['subtype_value']);
	}

	if (isset($params['nest_level'])) {
		$depth_level_counter = $params['nest_level'];
	} else {
		$depth_level_counter = 0;
	}

	$max_level = false;
	if (isset($params['max_level'])) {
		$max_level = $params['max_level'];
	}
	$list_tag = false;
	if (isset($params['list_tag'])) {
		$list_tag = $params['list_tag'];
	}
	$list_item_tag = false;
	if (isset($params['list_item_tag'])) {
		$list_item_tag = $params['list_item_tag'];
	}

	$params['table'] = $table;
	// $add_ids1 = false;
	if (is_string($add_ids)) {
		$add_ids = explode(',', $add_ids);
	}

	if (isset($params['rel']) and $params['rel'] != false and isset($params['rel_id'])) {

		$table_assoc_name = db_get_assoc_table_name($params['rel']);
		$skip123 = true;

		$str0 = 'orderby=position asc&table=' . $table . '&limit=1000&data_type=category&what=categories&' . 'rel_id=' . intval($params['rel_id']) . '&rel=' . $table_assoc_name;
		$fors = get($str0);

	}

	//if (isset($params['debug'])) {
	//	d($params);
	//}

	ob_start();

	//  cache_save($fields, $function_cache_id, $cache_group = 'db');

	if ($skip123 == false) {

		content_helpers_getCaregoriesUlTree($parent, $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, $include_first, $content_type, $li_class_name = false, $add_ids, $orderby, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter, $max_level, $list_tag, $list_item_tag);
	} else {

		if ($fors != false and is_array($fors) and !empty($fors)) {
			foreach ($fors as $cat) {
				content_helpers_getCaregoriesUlTree($cat['id'], $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, $include_first = true, $content_type, $li_class_name = false, $add_ids, $orderby, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter, $max_level, $list_tag, $list_item_tag);
			}
		}
	}

	$content = ob_get_contents();
	//if (!isset($_GET['debug'])) {
	cache_save($content, $function_cache_id, $cache_group);
	//}
	ob_end_clean();
	print $content;
	return;
}

/**
 * `
 *
 * Prints the selected categories as an <UL> tree, you might pass several
 * options for more flexibility
 *
 * @param
 *        	array
 *
 * @param
 *        	boolean
 *
 * @author Peter Ivanov
 *
 * @version 1.0
 *
 * @since Version 1.0
 *
 */
function content_helpers_getCaregoriesUlTree($parent, $link = false, $active_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $orderby = false, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter = 0, $max_level = false, $list_tag = false, $list_item_tag = false) {

	$db_t_content = MW_TABLE_PREFIX . 'content';

	$table = $db_categories = MW_TABLE_PREFIX . 'categories';

	if ($parent == false) {

		$parent = (0);

		$include_first = false;
	} else {

		$parent = (int)$parent;
	}

	if (!is_array($orderby)) {

		$orderby[0] = 'position';

		$orderby[1] = 'ASC';
	}

	if (!empty($remove_ids)) {

		$remove_ids_q = implode(',', $remove_ids);

		$remove_ids_q = " and id not in ($remove_ids_q) ";
	} else {

		$remove_ids_q = false;
	}

	if (!empty($add_ids)) {

		$add_ids_q = implode(',', $add_ids);

		$add_ids_q = " and id in ($add_ids_q) ";
	} else {

		$add_ids_q = false;
	}
	//$add_ids_q = '';
	//$remove_ids_q =   '';
	if ($max_level != false and $depth_level_counter != false) {

		if (intval($depth_level_counter) >= intval($max_level)) {
			print '';
			return;
		}
	}

	if (isset($list_tag) == false or $list_tag == false) {
		$list_tag = 'ul';
	}

	if (isset($active_code_tag) == false or $active_code_tag == false) {
		$active_code_tag = '';
	}

	if (isset($list_item_tag) == false or $list_item_tag == false) {
		$list_item_tag = 'li';
	}

	if (empty($limit)) {
		$limit = array(0, 10);
	}

	$content_type = addslashes($content_type);
	$hard_limit = " LIMIT 300 ";
	$inf_loop_fix = "  and $table.id!=$table.parent_id  ";
	//	$inf_loop_fix = "     ";
	if ($content_type == false) {

		if ($include_first == true) {

			$sql = "SELECT * from $table where id=$parent  and data_type='category'   $remove_ids_q  $add_ids_q $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}  $hard_limit";

			// $sql = "SELECT * from $table where id=$parent  and data_type='category'   $remove_ids_q    $inf_loop_fix group by id   ";

		} else {

			$sql = "SELECT * from $table where parent_id=$parent and data_type='category'   $remove_ids_q $add_ids_q $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}   $hard_limit";
		}
	} else {

		if ($include_first == true) {

			$sql = "SELECT * from $table where id=$parent   $remove_ids_q $add_ids_q   $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}  $hard_limit";
		} else {

			$sql = "SELECT * from $table where parent_id=$parent and data_type='category' and (categories_content_type='$content_type' or categories_content_type='inherit' )   $remove_ids_q  $add_ids_q $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}   $hard_limit";
		}
	}

	if (!empty($limit)) {

		$my_offset = $limit[1] - $limit[0];

		$my_limit_q = " limit  {$limit[0]} , $my_offset  ";
	} else {

		$my_limit_q = false;
	}
	$output = '';
	//$children_of_the_main_parent = get_category_items($parent, $type = 'category_item', $visible_on_frontend, $limit);
	//

	//
	$q = db_query($sql, $cache_id = 'content_helpers_getCaregoriesUlTree_parent_cats_q_' . crc32($sql), 'categories/' . intval($parent));
	//$q = db_query($sql);

	// $q = $this->core_model->dbQuery ( $sql, $cache_id =
	// 'content_helpers_getCaregoriesUlTree_parent_cats_q_' . md5 ( $sql ),
	// 'categories/' . intval ( $parent ) );

	$result = $q;

	//

	$only_with_content2 = $only_with_content;

	$do_not_show_next = false;

	$chosen_categories_array = array();

	if (isset($result) and is_array($result) and !empty($result)) {

		// $output = "<ul>";
		$depth_level_counter++;
		$i = 0;

		foreach ($result as $item) {

			$do_not_show = false;

			if ($only_with_content == true) {

				$check_in_content = false;

				if (is_array($only_with_content) and !empty($only_with_content)) {

					$content_ids_of_the_1_parent = $this -> categories_model -> getToTableIds($only_with_content[0], $limit);

					$content_ids_of_the_1_parent_o = $content_ids_of_the_1_parent;

					if (!empty($content_ids_of_the_1_parent)) {

						$only_with_content3 = array();

						$chosen_categories_array = array();

						foreach ($only_with_content2 as $only_with_content2_i) {

							$only_with_content2_i = str_ireplace('{id}', $item['id'], $only_with_content2_i);

							$only_with_content2_i = intval($only_with_content2_i);

							$chosen_categories_array[] = $only_with_content2_i;
						}

						if (count($chosen_categories_array) > 1) {

							// array_shift ( $chosen_categories_array );
						}

						$chosen_categories_array_i = implode(',', $chosen_categories_array);

						$children_of_the_next_parent = get_category_items_ids($only_with_content[0], $limit);

						$children_of_the_next_parent_i = implode(',', $children_of_the_next_parent);

						$children_of_the_next_parent_qty = count($chosen_categories_array);

						$q = "
						select id , count(rel_id) as qty from $db_categories where
						rel_id in($children_of_the_next_parent_i)
						and parent_id  IN ($chosen_categories_array_i)
						and data_type =  'category_item'
						group by rel_id
						$my_limit_q
						";

						$total_count_array = array();

						if (!empty($q)) {

							foreach ($q as $q1) {
								$qty = intval($q1['qty']);

								if (($children_of_the_next_parent_qty) == $qty) {
									$total_count_array[] = $q1;
								}
							}
						}

						$results_count = count($total_count_array);

						$content_ids_of_the_1_parent_i = implode(',', $children_of_the_main_parent);

						$chosen_categories_array_i = implode(',', $chosen_categories_array);

						$result[$i]['content_count'] = $results_count;

						$item['content_count'] = $results_count;
					} else {

						$results_count = 0;
					}

					$result[$i]['content_count'] = $results_count;

					$item['content_count'] = $results_count;
				} else {

					$do_not_show = false;
				}
			}

			$i++;
		}

		if ($do_not_show == false) {

			$print1 = false;
			if (trim($list_tag) != '') {
				if ($ul_class_name == false) {

					$print1 = "<{$list_tag}  class='category_tree depth-{$depth_level_counter}'>";
				} else {

					$print1 = "<{$list_tag} class='$ul_class_name depth-{$depth_level_counter}'>";
				}
			}
			print $print1;
			// print($type);
			foreach ($result as $item) {

				if ($only_with_content == true) {

					$do_not_show = false;

					$check_in_content = false;
					$childern_content = array();

					$do_not_show = false;
					// print($type);

					if (!empty($childern_content)) {

						$do_not_show = false;
					} else {

						$do_not_show = true;
					}
				} else {

					$do_not_show = false;
				}
				$iid = $item['id'];
				if ($do_not_show == false) {

					$output = $output . $item['title'];

					if ($li_class_name == false) {

						print "<{$list_item_tag} class='category_element depth-{$depth_level_counter} item_{$iid}'  data-category-id='{$item['id']}' data-category-parent-id='{$item['parent_id']}' data-item-id='{$item['id']}'  data-to-table='{$item['rel']}'  data-to-table-id='{$item['rel_id']}'    data-categories-type='{$item['data_type']}'>";
					} else {

						print "<{$list_item_tag} class='$li_class_name  category_element depth-{$depth_level_counter} item_{$iid}' data-item-id='{$item['id']}' data-category-id='{$item['id']}'  data-to-table='{$item['rel']}'  data-to-table-id='{$item['rel_id']}'  data-categories-type='{$item['data_type']}'   >";
					}
				}

				if ($do_not_show == false) {

					if ($link != false) {

						$to_print = false;

						$to_print = str_ireplace('{id}', $item['id'], $link);
						$to_print = str_ireplace('{url}', category_link($item['id']), $to_print);

						$to_print = str_ireplace('{categories_url}', category_link($item['id']), $to_print);
						$to_print = str_ireplace('{nest_level}', 'depth-' . $depth_level_counter, $to_print);

						$to_print = str_ireplace('{title}', $item['title'], $to_print);

						//$to_print = str_ireplace('{title2}', $item ['title2'], $to_print);
						// $to_print = str_ireplace('{title3}', $item ['title3'], $to_print);

						$to_print = str_ireplace('{categories_content_type}', trim($item['categories_content_type']), $to_print);

						//   $to_print = str_ireplace('{content_count}', $item ['content_count'], $to_print);
						$active_found = false;

						if (is_string($active_ids)) {
							$active_ids = explode(',', $active_ids);
						}

						if (is_array($active_ids) == true) {
							$active_ids = array_trim($active_ids);
							//d($active_ids);

							foreach ($active_ids as $value_active_cat) {
								if ($value_active_cat != '') {
									$value_active_cat = intval($value_active_cat);
									if (intval($item['id']) == $value_active_cat) {
										$active_found = $value_active_cat;
										//d($value_active_cat);
									}
								}
							}

							if ($active_found == true) {

								$to_print = str_ireplace('{active_code}', $active_code, $to_print);
							} else {

								$to_print = str_ireplace('{active_code}', '', $to_print);
							}
						} else {

							$to_print = str_ireplace('{active_code}', '', $to_print);
						}

						if (is_array($remove_ids) == true) {

							if (in_array($item['id'], $remove_ids)) {

								if ($removed_ids_code == false) {

									$to_print = false;
								} else {

									$to_print = str_ireplace('{removed_ids_code}', $removed_ids_code, $to_print);
								}
							} else {

								$to_print = str_ireplace('{removed_ids_code}', '', $to_print);
							}
						}

						if (strval($to_print) == '') {

							print $item['title'];
						} else {

							print $to_print;
						}
					} else {

						print $item['title'];
					}

					// $parent, $link = false, $active_ids = false,
					// $active_code = false, $remove_ids = false,
					// $removed_ids_code = false, $ul_class_name = false,
					// $include_first = false, $content_type = false,
					// $li_class_name = false) {
					// $li_class_name = false, $add_ids = false, $orderby =
					// false, $only_with_content = false
					$children_of_the_main_parent1 = array();
					// $children_of_the_main_parent1 = get_category_children($item ['id'], $type = 'category', $visible_on_frontend = false);
					// p($children_of_the_main_parent1 );
					$remove_ids[] = $item['id'];
					if (!empty($children_of_the_main_parent1)) {
						foreach ($children_of_the_main_parent1 as $children_of_the_main_par) {

							// $remove_ids[] = $children_of_the_main_par;
							// $children = CI::model ( 'content'
							// )->content_helpers_getCaregoriesUlTree (
							// $children_of_the_main_par, $link, $active_ids,
							// $active_code, $remove_ids, $removed_ids_code,
							// $ul_class_name, false, $content_type,
							// $li_class_name, $add_ids, $orderby,
							// $only_with_content, $visible_on_frontend );
						}
					}

					$children = content_helpers_getCaregoriesUlTree($item['id'], $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, false, $content_type, $li_class_name, $add_ids = false, $orderby, $only_with_content, $visible_on_frontend, $depth_level_counter, $max_level, $list_tag, $list_item_tag);

					print "</{$list_item_tag}>";
				}
			}
			if (trim($list_tag) != '') {
				print "</{$list_tag}>";
			}
		}
	} else {

	}
}

function OOOOOOLD_content_helpers_getCaregoriesUlTree($parent, $link = false, $active_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $orderby = false, $only_with_content = false, $visible_on_frontend = false, $depth_level_counter = 0, $max_level = false, $list_tag = false, $list_item_tag = false) {

	$db_t_content = MW_TABLE_PREFIX . 'content';

	$table = $db_categories = MW_TABLE_PREFIX . 'categories';

	if ($parent == false) {

		$parent = (0);

		$include_first = false;
	} else {

		$parent = (int)$parent;
	}

	if (!is_array($orderby)) {

		$orderby[0] = 'position';

		$orderby[1] = 'ASC';
	}

	if (!empty($remove_ids)) {

		$remove_ids_q = implode(',', $remove_ids);

		$remove_ids_q = " and id not in ($remove_ids_q) ";
	} else {

		$remove_ids_q = false;
	}

	if (!empty($add_ids)) {

		$add_ids_q = implode(',', $add_ids);

		$add_ids_q = " and id in ($add_ids_q) ";
	} else {

		$add_ids_q = false;
	}

	if ($max_level != false and $depth_level_counter != false) {

		if (intval($depth_level_counter) >= intval($max_level)) {
			print '';
			return;
		}
	}

	if (isset($list_tag) == false or $list_tag == false) {
		$list_tag = 'ul';
	}

	if (isset($active_code_tag) == false or $active_code_tag == false) {
		$active_code_tag = '';
	}

	if (isset($list_item_tag) == false or $list_item_tag == false) {
		$list_item_tag = 'li';
	}

	$content_type = addslashes($content_type);
	$hard_limit = " LIMIT 300 ";
	$inf_loop_fix = "  and $table.id!=$table.parent_id  ";
	$inf_loop_fix = "     ";
	if ($content_type == false) {

		if ($include_first == true) {

			$sql = "SELECT * from $table where id=$parent  and data_type='category'   $remove_ids_q  $add_ids_q $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}  $hard_limit";
		} else {

			$sql = "SELECT * from $table where parent_id=$parent and data_type='category'   $remove_ids_q $add_ids_q $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}   $hard_limit";
		}
	} else {

		if ($include_first == true) {

			$sql = "SELECT * from $table where id=$parent and (categories_content_type='$content_type' or categories_content_type='inherit' )  $remove_ids_q $add_ids_q   $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}  $hard_limit";
		} else {

			$sql = "SELECT * from $table where parent_id=$parent and data_type='category' and (categories_content_type='$content_type' or categories_content_type='inherit' )   $remove_ids_q  $add_ids_q $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}   $hard_limit";
		}
	}

	if (empty($limit)) {
		$limit = array(0, 10);
	}

	if (!empty($limit)) {

		$my_offset = $limit[1] - $limit[0];

		$my_limit_q = " limit  {$limit[0]} , $my_offset  ";
	} else {

		$my_limit_q = false;
	}
	$output = '';
	//$children_of_the_main_parent = get_category_items($parent, $type = 'category_item', $visible_on_frontend, $limit);
	//

	$q = db_query($sql, $cache_id = 'content_helpers_getCaregoriesUlTree_parent_cats_q_' . crc32($sql), 'categories/' . intval($parent));
	// $q = $this->core_model->dbQuery ( $sql, $cache_id =
	// 'content_helpers_getCaregoriesUlTree_parent_cats_q_' . md5 ( $sql ),
	// 'categories/' . intval ( $parent ) );

	$result = $q;

	//

	$only_with_content2 = $only_with_content;

	$do_not_show_next = false;

	$chosen_categories_array = array();

	if (isset($result) and is_array($result) and !empty($result)) {

		// $output = "<ul>";
		$depth_level_counter++;
		$i = 0;

		foreach ($result as $item) {

			$do_not_show = false;

			if ($only_with_content == true) {

				$check_in_content = false;

				if (is_array($only_with_content) and !empty($only_with_content)) {

					$content_ids_of_the_1_parent = $this -> categories_model -> getToTableIds($only_with_content[0], $limit);

					$content_ids_of_the_1_parent_o = $content_ids_of_the_1_parent;

					if (!empty($content_ids_of_the_1_parent)) {

						$only_with_content3 = array();

						$chosen_categories_array = array();

						foreach ($only_with_content2 as $only_with_content2_i) {

							$only_with_content2_i = str_ireplace('{id}', $item['id'], $only_with_content2_i);

							$only_with_content2_i = intval($only_with_content2_i);

							$chosen_categories_array[] = $only_with_content2_i;
						}

						if (count($chosen_categories_array) > 1) {

							// array_shift ( $chosen_categories_array );
						}

						$chosen_categories_array_i = implode(',', $chosen_categories_array);

						$children_of_the_next_parent = get_category_items_ids($only_with_content[0], $limit);

						$children_of_the_next_parent_i = implode(',', $children_of_the_next_parent);

						$children_of_the_next_parent_qty = count($chosen_categories_array);

						$q = "
						select id , count(rel_id) as qty from $db_categories where
						rel_id in($children_of_the_next_parent_i)
						and parent_id  IN ($chosen_categories_array_i)
						and data_type =  'category_item'
						group by rel_id
						$my_limit_q
						";

						$total_count_array = array();

						if (!empty($q)) {

							foreach ($q as $q1) {
								$qty = intval($q1['qty']);

								if (($children_of_the_next_parent_qty) == $qty) {
									$total_count_array[] = $q1;
								}
							}
						}

						$results_count = count($total_count_array);

						$content_ids_of_the_1_parent_i = implode(',', $children_of_the_main_parent);

						$chosen_categories_array_i = implode(',', $chosen_categories_array);

						$result[$i]['content_count'] = $results_count;

						$item['content_count'] = $results_count;
					} else {

						$results_count = 0;
					}

					$result[$i]['content_count'] = $results_count;

					$item['content_count'] = $results_count;
				} else {

					$do_not_show = false;
				}
			}

			$i++;
		}

		if ($do_not_show == false) {

			$print1 = false;
			if (trim($list_tag) != '') {
				if ($ul_class_name == false) {

					$print1 = "<{$list_tag}  class='category_tree depth-{$depth_level_counter}'>";
				} else {

					$print1 = "<{$list_tag} class='$ul_class_name depth-{$depth_level_counter}'>";
				}
			}
			print $print1;
			// print($type);
			foreach ($result as $item) {

				if ($only_with_content == true) {

					$do_not_show = false;

					$check_in_content = false;
					$childern_content = array();

					$do_not_show = false;
					// print($type);

					if (!empty($childern_content)) {

						$do_not_show = false;
					} else {

						$do_not_show = true;
					}
				} else {

					$do_not_show = false;
				}
				$iid = $item['id'];
				if ($do_not_show == false) {

					$output = $output . $item['title'];

					if ($li_class_name == false) {

						print "<{$list_item_tag} class='category_element depth-{$depth_level_counter} item_{$iid}'  data-category-id='{$item['id']}' data-category-parent-id='{$item['parent_id']}' data-item-id='{$item['id']}'  data-to-table='{$item['rel']}'  data-to-table-id='{$item['rel_id']}'    data-categories-type='{$item['data_type']}'>";
					} else {

						print "<{$list_item_tag} class='$li_class_name  category_element depth-{$depth_level_counter} item_{$iid}' data-item-id='{$item['id']}' data-category-id='{$item['id']}'  data-to-table='{$item['rel']}'  data-to-table-id='{$item['rel_id']}'  data-categories-type='{$item['data_type']}'   >";
					}
				}

				if ($do_not_show == false) {

					if ($link != false) {

						$to_print = false;

						$to_print = str_ireplace('{id}', $item['id'], $link);

						$to_print = str_ireplace('{categories_url}', category_link($item['id']), $to_print);
						$to_print = str_ireplace('{nest_level}', 'depth-' . $depth_level_counter, $to_print);

						$to_print = str_ireplace('{title}', $item['title'], $to_print);

						//$to_print = str_ireplace('{title2}', $item ['title2'], $to_print);
						// $to_print = str_ireplace('{title3}', $item ['title3'], $to_print);

						$to_print = str_ireplace('{categories_content_type}', trim($item['categories_content_type']), $to_print);

						//   $to_print = str_ireplace('{content_count}', $item ['content_count'], $to_print);

						if (is_array($active_ids) == true) {

							if (in_array($item['id'], $active_ids)) {

								$to_print = str_ireplace('{active_code}', $active_code, $to_print);
							} else {

								$to_print = str_ireplace('{active_code}', '', $to_print);
							}
						} else {

							$to_print = str_ireplace('{active_code}', '', $to_print);
						}

						if (is_array($remove_ids) == true) {

							if (in_array($item['id'], $remove_ids)) {

								if ($removed_ids_code == false) {

									$to_print = false;
								} else {

									$to_print = str_ireplace('{removed_ids_code}', $removed_ids_code, $to_print);
								}
							} else {

								$to_print = str_ireplace('{removed_ids_code}', '', $to_print);
							}
						}

						if (strval($to_print) == '') {

							print $item['title'];
						} else {

							print $to_print;
						}
					} else {

						print $item['title'];
					}

					// $parent, $link = false, $active_ids = false,
					// $active_code = false, $remove_ids = false,
					// $removed_ids_code = false, $ul_class_name = false,
					// $include_first = false, $content_type = false,
					// $li_class_name = false) {
					// $li_class_name = false, $add_ids = false, $orderby =
					// false, $only_with_content = false
					$children_of_the_main_parent1 = array();
					// $children_of_the_main_parent1 = get_category_children($item ['id'], $type = 'category', $visible_on_frontend = false);
					// p($children_of_the_main_parent1 );
					$remove_ids[] = $item['id'];
					if (!empty($children_of_the_main_parent1)) {
						foreach ($children_of_the_main_parent1 as $children_of_the_main_par) {

							// $remove_ids[] = $children_of_the_main_par;
							// $children = CI::model ( 'content'
							// )->content_helpers_getCaregoriesUlTree (
							// $children_of_the_main_par, $link, $active_ids,
							// $active_code, $remove_ids, $removed_ids_code,
							// $ul_class_name, false, $content_type,
							// $li_class_name, $add_ids, $orderby,
							// $only_with_content, $visible_on_frontend );
						}
					}

					$children = content_helpers_getCaregoriesUlTree($item['id'], $link, $active_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, false, $content_type, $li_class_name, $add_ids = false, $orderby, $only_with_content, $visible_on_frontend, $depth_level_counter, $max_level, $list_tag, $list_item_tag);

					print "</{$list_item_tag}>";
				}
			}
			if (trim($list_tag) != '') {
				print "</{$list_tag}>";
			}
		}
	} else {

	}
}

function get_category_items_for_parent_category($parent_id, $type = false, $visible_on_frontend = false, $limit = false) {

	$categories_id = intval($parent_id);

	$cache_group = 'categories/' . $categories_id;

	$table = MW_TABLE_PREFIX . 'categories';
	$table_items = MW_TABLE_PREFIX . 'categories_items';

	$db_t_content = MW_TABLE_PREFIX . 'content';

	if (isset($orderby) == false) {
		$orderby = array();
		$orderby[0] = 'updated_on';

		$orderby[1] = 'DESC';
	}

	if (intval($parent_id) == 0) {

		return false;
	}

	if (!empty($limit)) {

		$my_offset = $limit[1] - $limit[0];

		$my_limit_q = " limit  {$limit[0]} , $my_offset  ";
	} else {

		$my_limit_q = false;
	}

	$data = array();

	$data['parent_id'] = $parent_id;

	if ($type != FALSE) {
		// var_Dump($type);

		$data['data_type'] = $type;

		$type_q = " and data_type='$type'   ";
	} else {
		// @doto: remove the hard coded part here by revieweing all the other
		// files for diferent values of $type
		$type = 'category_item';

		// var_Dump($type);

		$data['data_type'] = $type;

		$type_q = " and data_type='$type'   ";
	}
	$visible_on_frontend_q = '';
	if ($visible_on_frontend == true) {

		$visible_on_frontend_q = " and rel_id in (select id from $db_t_content where visible_on_frontend='y') ";
	}

	// $save = $this->categoriesGet ( $data = $data, $orderby = $orderby );

	$cache_group = 'categories/' . $parent_id;
	$q = " SELECT id,    parent_id from $table_items where parent_id= $parent_id   $type_q  $visible_on_frontend_q $my_limit_q ";
	// var_dump($q);
	$q_cache_id = __FUNCTION__ . crc32($q);
	// var_dump($q_cache_id);
	$save = db_query($q, $q_cache_id, $cache_group);

	// $save = $this->getSingleItem ( $parent_id );
	if (empty($save)) {
		return false;
	}
	$to_return = array();
	if (!empty($save)) {
		$to_return[] = $parent_id;
	}
	if (is_array($save) and !empty($save)) {
		foreach ($save as $item) {
			$to_return[] = $item['id'];
		}
	}

	$to_return = array_unique($to_return);

	return $to_return;
}

function get_category_items_ids($root, $limit = false) {
	if (!is_array($root)) {
		$root = intval($root);
		if (intval($root) == 0) {

			return false;
		}
	}

	$table = MW_TABLE_PREFIX . 'categories';
	$db_categories_items = MW_TABLE_PREFIX . 'categories_items';

	$db_t_content = MW_TABLE_PREFIX . 'content';

	$ids = array();

	if (!empty($limit)) {

		$my_offset = $limit[1] - $limit[0];

		$my_limit_q = " limit  {$limit[0]} , $my_offset  ";
	} else {

		$my_limit_q = " limit  0 , 500  ";
	}

	$data = array();

	$data['parent_id'] = $root;
	if (!is_array($root)) {
		$root_q = " parent_id=$root ";
		$cache_group = 'categories/' . $root;
	} else {
		$root_i = implode(',', $root);
		$root_q = " parent_id in ($root_i) ";
		$cache_group = 'categories/global';
	}

	$q = " SELECT id, parent_id,rel_id from $db_categories_items where $root_q $visible_on_frontend_q and data_type='category_item'  group by rel_id   $my_limit_q ";

	// var_dump($q);
	$taxonomies = db_query($q, __FUNCTION__ . crc32($q), $cache_group);

	// var_dump($taxonomies);
	// print 'asds';;

	if (!empty($taxonomies)) {

		foreach ($taxonomies as $item) {

			if (intval($item['rel_id']) != 0) {

				$ids[] = $item['rel_id'];
			}

			/*
			 * if ($non_recursive == false) { $next = $this->getToTableIds (
			 * $item ['id'], $visible_on_frontend ); if (! empty ( $next )) {
			 * foreach ( $next as $n ) { if ($n != '') { $ids [] = $n; } } } }
			 */
		}
	}
	// p($ids);
	if (!empty($ids)) {

		$ids = array_unique($ids);

		asort($ids);

		return $ids;
	} else {

		return false;
	}
}

api_expose('save_category');

function save_category($data, $preserve_cache = false) {

	$adm = is_admin();
	if ($adm == false) {
		error('Ony admin can save category');
	}

	$table = MW_TABLE_PREFIX . 'categories';
	$table_items = MW_TABLE_PREFIX . 'categories_items';

	$content_ids = false;

	if (isset($data['rel']) and ($data['rel'] == '') or !isset($data['rel'])) {
		$data['rel'] = 'content';
	}
	if (isset($data['content_id'])) {

		if (is_array($data['content_id']) and !empty($data['content_id']) and trim($data['data_type']) != '') {
			$content_ids = $data['content_id'];
		}
	}
	$no_position_fix = false;
	if (isset($data['rel']) and isset($data['rel_id']) and trim($data['rel']) != '' and trim($data['rel_id']) != '') {

		$table = $table_items;
		$no_position_fix = true;
	}
	if (isset($data['table']) and ($data['table'] != '')) {
		$table = $data['table'];
	}
	//$data['debug'] = '1';
	//d($data);

	if (isset($data['rel']) and isset($data['rel_id']) and trim($data['rel']) == 'content' and intval($data['rel_id']) != 0) {

		$cs = array();
		$cs['id'] = intval($data['rel_id']);
		$cs['subtype'] = 'dynamic';
		$table_c = MW_TABLE_PREFIX . 'content';
		$save = save_data($table_c, $cs);

	}

	$save = save_data($table, $data);

	cache_clean_group('categories' . DIRECTORY_SEPARATOR . $save);
	if (isset($data['id'])) {
		cache_clean_group('categories' . DIRECTORY_SEPARATOR . intval($data['id']));
	}
	if (isset($data['parent_id'])) {
		cache_clean_group('categories' . DIRECTORY_SEPARATOR . intval($data['parent_id']));
	}
	if (intval($save) == 0) {

		return false;
	}

	if (isset($content_ids) and !empty($content_ids)) {

		$content_ids = array_unique($content_ids);

		// p($content_ids, 1);

		$data_type = trim($data['data_type']) . '_item';

		$content_ids_all = implode(',', $content_ids);

		$q = "delete from $table where rel='content'
		and content_type='post'
		and parent_id=$save
		and  data_type ='{$data_type}' ";

		// p($q,1);

		dq_q($q);

		foreach ($content_ids as $id) {

			$item_save = array();

			$item_save['rel'] = 'content';

			$item_save['rel_id'] = $id;

			$item_save['data_type'] = $data_type;

			$item_save['content_type'] = 'post';

			$item_save['parent_id'] = intval($save);

			$item_save = save_data($table_items, $item_save);

			cache_clean_group('content' . DIRECTORY_SEPARATOR . $id);
		}
	}
	if ($no_position_fix == false) {
		//$this->categoriesFixPositionsForId($save);
	}
	// $this->core_model->cleanCacheGroup ( 'categories' );

	if ($preserve_cache == false) {

		// $this->core_model->cleanCacheGroup ( 'categories' );
		cache_clean_group('categories' . DIRECTORY_SEPARATOR . $save);
		cache_clean_group('categories' . DIRECTORY_SEPARATOR . '0');
		cache_clean_group('categories' . DIRECTORY_SEPARATOR . 'global');
	}

	return $save;
}

api_expose('delete_category');

function delete_category($data) {

	$adm = is_admin();
	if ($adm == false) {
		error('Error: not logged in as admin.');
	}

	if (isset($data['id'])) {
		$c_id = intval($data['id']);
		db_delete_by_id('categories', $c_id);
		db_delete_by_id('categories', $c_id, 'parent_id');
		db_delete_by_id('categories_items', $c_id, 'parent_id');

		//d($c_id);
	}
}

function get_categories_array($params, $data_type = 'categories') {

	return get_categories($params, $data_type);
}

function get_categories($params, $data_type = 'categories') {
	$params2 = array();
	$rel_id = 0;
	if (is_string($params)) {
		$params = parse_str($params, $params2);
		$params = $options = $params2;
		extract($params);
	}
	if (isset($params['rel_id'])) {
		$rel_id = $params['rel_id'];
	}

	$table = MW_TABLE_PREFIX . 'categories';
	$table_items = MW_TABLE_PREFIX . 'categories_items';

	$data = $params;
	$data_type_q = false;

	$data['table'] = $table;
	if (isset($params['id'])) {
		$data['cache_group'] = $cache_group = 'categories/' . $params['id'];
	} else {
		$data['cache_group'] = $cache_group = 'categories/global';

	}
	//$data['only_those_fields'] = array('parent_id');

	$data = get($data);
	return $data;

}

function get_category_items($params, $data_type = 'categories') {

	$rel_id = 0;
	if (is_string($params)) {
		$params = parse_str($params, $params2);
		$params = $options = $params2;
	}

	$table = MW_TABLE_PREFIX . 'categories';
	$table_items = MW_TABLE_PREFIX . 'categories_items';

	$data = $params;
	$data_type_q = false;
	if ($data_type == 'categories') {
		$data['data_type'] = 'category_item';
		$data_type_q = "and data_type = 'category_item' ";
	}

	if ($data_type == 'tags') {
		$data['data_type'] = 'tag_item';
		$data_type_q = "and data_type = 'tag_item' ";
	}
	$data['table'] = $table_items;
	//$data['debug'] = $table;
	//$data['cache_group'] = $cache_group = 'categories/' . $rel_id;
	//$data['only_those_fields'] = array('parent_id');

	$data = get($data);
	return $data;
	//$q = "select parent_id from $table_items where  rel='content' and rel_id=$content_id $data_type_q ";
	// var_dump($q);
	//
	//
	//
	//
	//$data = db_query($q, __FUNCTION__ . crc32($q), $cache_group = 'content/' . $content_id);
	// var_dump ( $data );
	$results = false;
	if (!empty($data)) {
		$results = array();
		foreach ($data as $item) {
			$results[] = $item['parent_id'];
		}
		$results = array_unique($results);
	}
	//cache_save($results, $function_cache_id, $cache_group);
	return $results;
}

api_expose('reorder_categories');

function reorder_categories($data) {

	$adm = is_admin();
	if ($adm == false) {
		error('Error: not logged in as admin.');
	}

	$table = MW_TABLE_PREFIX . 'categories';
	foreach ($data as $value) {
		if (is_arr($value)) {
			$indx = array();
			$i = 0;
			foreach ($value as $value2) {
				$indx[$i] = $value2;
				$i++;
			}

			db_update_position($table, $indx);
			return true;
			// d($indx);
		}
	}
}

function get_categories_for_content($content_id, $data_type = 'categories') {
	if (intval($content_id) == 0) {

		return false;
	}

	$get_category = get_categories_array('data_type=category&rel=content&rel_id=' . ($content_id));
	return $get_category;
	$function_cache_id = false;

	$args = func_get_args();

	foreach ($args as $k => $v) {

		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);
	}
	$content_id = intval($content_id);
	$cache_group = 'content/' . $content_id;

	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);

	$cache_content = cache_get_content($function_cache_id, $cache_group);

	if (($cache_content) != false) {

		return $cache_content;
	}

	$table = MW_TABLE_PREFIX . 'categories';
	$table_items = MW_TABLE_PREFIX . 'categories_items';

	$data = array();

	$data['rel'] = 'content';

	$data['rel_id'] = $content_id;
	$data_type_q = false;
	if ($data_type == 'categories') {
		$data['data_type'] = 'category_item';
		$data_type_q = "and data_type = 'category_item' ";
	}

	if ($data_type == 'tags') {
		$data['data_type'] = 'tag_item';
		$data_type_q = "and data_type = 'tag_item' ";
	}

	$q = "select parent_id from $table_items where  rel='content' and rel_id=$content_id $data_type_q ";
	// var_dump($q);
	$data = db_query($q, __FUNCTION__ . crc32($q), $cache_group = 'content/' . $content_id);
	// var_dump ( $data );
	$results = false;
	if (!empty($data)) {
		$results = array();
		foreach ($data as $item) {
			$results[] = $item['parent_id'];
		}
		$results = array_unique($results);
	}
	cache_save($results, $function_cache_id, $cache_group);
	return $results;
}

function category_link($id) {

	if (intval($id) == 0) {

		return false;
	}

	$function_cache_id = '';

	$args = func_get_args();

	foreach ($args as $k => $v) {

		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);
	}
	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);

	$categories_id = intval($id);
	$cache_group = 'categories/' . $categories_id;

	$cache_content = cache_get_content($function_cache_id, $cache_group);

	if (($cache_content) != false) {

		return $cache_content;
	} else {
		$table = MW_TABLE_PREFIX . 'categories';
		$c_infp = get_category_by_id($id);
		if (!isset($c_infp['rel'])) {
			return;
		}

		if (trim($c_infp['rel']) != 'content') {
			return;
		}

		$content = get_page_for_category($id);

		if (!empty($content)) {
			$url = $content['url'];
			if ($content['content_type'] == 'page') {
				if (function_exists('page_link')) {
					$url = page_link($content['id']);
				}
			}

			if ($content['content_type'] == 'post') {
				if (function_exists('post_link')) {
					$url = post_link($content['id']);
				}
			}
		} else {
			if (!empty($c_infp) and isset($c_infp['rel']) and trim($c_infp['rel']) == 'content') {
				db_delete_by_id($table, $id);
			}
		}

		if (isset($url) != false) {
			$url = $url . '/category:' . $id;
			cache_save($url, $function_cache_id, $cache_group);

			return $url;
		}

		return;
	}

	//todo delete

	$function_cache_id = '';

	$args = func_get_args();

	foreach ($args as $k => $v) {

		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);
	}
	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);

	$categories_id = intval($id);
	$cache_group = 'categories/' . $categories_id;

	$cache_content = cache_get_content($function_cache_id, $cache_group);

	if (($cache_content) != false) {

		return $cache_content;
	} else {

		$data = array();

		$data['id'] = $id;

		$data = get_category_by_id($id);

		if (empty($data)) {

			return false;
		}
		//$this->load->model ( 'Content_model', 'content_model' );

		$table = MW_TABLE_PREFIX . 'categories';
		$db_t_content = MW_TABLE_PREFIX . 'content';

		$content = array();

		$content['subtype'] = 'dynamic';

		$content['subtype_value'] = $id;

		//$orderby = array ('id', 'desc' );

		$q = " select * from $db_t_content where subtype ='dynamic' and subtype_value={$id} limit 0,1";
		//p($q,1);
		$q = db_query($q, __FUNCTION__ . crc32($q), $cache_group);

		//$content = $this->content_model->getContentAndCache ( $content, $orderby );

		$content = $q[0];

		$url = false;

		$parent_ids = get_category_parents($data['id']);
		$parent_ids = array_rpush($parent_ids, $data['id']);
		foreach ($parent_ids as $item) {

			$content = array();

			$content['subtype'] = 'dynamic';

			$content['subtype_value'] = $item;

			$orderby = array('id', 'desc');

			$q = " select * from $db_t_content where subtype ='dynamic' and subtype_value={$item} limit 0,1";
			//p($q);
			$q = db_query($q, __FUNCTION__ . crc32($q), $cache_group);

			//$content = $this->content_model->getContentAndCache ( $content, $orderby );

			$content = $q[0];

			//$content = $content [0];

			$url = false;

			if (!empty($content)) {

				if ($content['content_type'] == 'page') {
					if (function_exists('page_link')) {
						$url = page_link($content['id']);
						//$url = $url . '/category:' . $data ['title'];

						$str = $data['title'];
						if (function_exists('mb_strtolower')) {
							$str = mb_strtolower($str, "UTF-8");
						} else {
							$str = strtolower($str);
						}

						$string1 = ($str);

						$url = $url . '/' . url_title($string1) . '/categories:' . $data['id'];

						//$url = $url . '/categories:' . $data ['id'];
					}
				}
				if ($content['content_type'] == 'post') {
					if (function_exists('post_link')) {
						$url = post_link($content['id']);
					}
				}
			}

			//if ($url != false) {
			cache_save($url, $function_cache_id, $cache_group);
			return $url;
			//}
		}

		return false;
	}

	//var_dump ( $parent_ids );
}

/**

 * @desc Get a single row from the categories_table by given ID and returns it as one dimensional array

 * @param int

 * @return array

 * @author      Peter Ivanov

 * @version 1.0

 * @since Version 1.0

 */
function get_category_by_id($id = 0) {

	if ($id == 0) {
		return false;
	}

	$id = intval($id);

	$function_cache_id = false;

	$args = func_get_args();

	foreach ($args as $k => $v) {

		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);
	}

	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);

	$categories_id = intval($id);
	$cache_group = 'categories/' . $categories_id;
	$cache_content = false;
	$cache_content = cache_get_content($function_cache_id, $cache_group);

	if (($cache_content) != false) {

		return $cache_content;
	}

	$table = MW_TABLE_PREFIX . 'categories';

	$id = intval($id);

	$q = " select * from $table where id = $id limit 0,1";

	$q = db_query($q);

	$q = $q[0];

	if (!empty($q)) {

		cache_save($q, $function_cache_id, $cache_group);
		//return $to_cache;

		return $q;
	} else {

		return false;
	}
}

function get_category_children($parent_id = 0, $type = false, $visible_on_frontend = false) {

	$categories_id = intval($parent_id);
	$cache_group = 'categories/' . $categories_id;

	$table = MW_TABLE_PREFIX . 'categories';

	$db_t_content = MW_TABLE_PREFIX . 'content';

	if (isset($orderby) == false) {
		$orderby = array();
		//$orderby[0] = 'updated_on';

		//$orderby[1] = 'DESC';

		$orderby[0] = 'position';

		$orderby[1] = 'asc';
	}

	if (intval($parent_id) == 0) {

		return false;
	}

	$data = array();

	$data['parent_id'] = $parent_id;

	if ($type != FALSE) {

		$data['data_type'] = $type;

		$type_q = " and data_type='$type'   ";
	} else {
		$type = 'category_item';
		$data['data_type'] = $type;

		$type_q = " and data_type='$type'   ";
	}

	$visible_on_frontend_q = false;
	//$save = $this->categoriesGet ( $data = $data, $orderby = $orderby );

	$cache_group = 'categories/' . $parent_id;
	$q = " SELECT id,  parent_id from $table where parent_id=$parent_id   ";
	//var_dump($q);
	$q_cache_id = __FUNCTION__ . crc32($q);
	//var_dump($q_cache_id);
	$save = db_query($q, $q_cache_id, $cache_group);

	//$save = $this->getSingleItem ( $parent_id );
	if (empty($save)) {
		return false;
	}
	$to_return = array();
	if (is_array($save) and !empty($save)) {
		foreach ($save as $item) {
			$to_return[] = $item['id'];
		}
	}

	$to_return = array_unique($to_return);

	return $to_return;
}

function get_page_for_category($category_id) {
	$category_id = intval($category_id);
	if ($category_id == 0) {
		return false;
	} else {

	}
	$category = get_category_by_id($category_id);
	if ($category != false) {
		if (isset($category["rel_id"]) and intval($category["rel_id"]) > 0) {
			if ($category["rel"] == 'content') {
				$res = get_content_by_id($category["rel_id"]);
				if (isarr($res)) {
					return $res;
				}
			}

		}

		if (isset($category["rel_id"]) and intval($category["rel_id"]) == 0 and intval($category["parent_id"]) > 0) {
			$category1 = get_category_parents($category["id"]);
			if (isarr($category1)) {
				foreach ($category1 as $value) {
					if (intval($value) != 0) {
						$category2 = get_category_by_id($value);
						if (isset($category2["rel_id"]) and intval($category2["rel_id"]) > 0) {
							if ($category2["rel"] == 'content') {
								$res = get_content_by_id($category2["rel_id"]);
								if (isarr($res)) {
									return $res;
								}
							}

						}
						//	d($category2);
					}
				}
			}

		}
	}

	//d($res);

}

function get_category_parents($id = 0, $without_main_parrent = false, $data_type = 'category') {

	if (intval($id) == 0) {

		return FALSE;
	}

	$table = MW_TABLE_PREFIX . 'categories';

	$ids = array();

	$data = array();

	if (isset($without_main_parrent) and $without_main_parrent == true) {

		$with_main_parrent_q = " and parent_id<>0 ";
	} else {

		$with_main_parrent_q = false;
	}
	$id = intval($id);
	$q = " select id, parent_id  from $table where id = $id and  data_type='{$data_type}'  $with_main_parrent_q ";

	$taxonomies = db_query($q, $cache_id = __FUNCTION__ . crc32($q), $cache_group = 'categories/' . $id);

	//var_dump($q);
	//  var_dump($taxonomies);
	//  exit;

	if (!empty($taxonomies)) {

		foreach ($taxonomies as $item) {

			if (intval($item['id']) != 0) {

				$ids[] = $item['parent_id'];
			}
			if ($item['parent_id'] != $item['id']) {
				$next = get_category_parents($item['parent_id'], $without_main_parrent);

				if (!empty($next)) {

					foreach ($next as $n) {

						if ($n != '') {

							$ids[] = $n;
						}
					}
				}
			}
		}
	}

	if (!empty($ids)) {

		$ids = array_unique($ids);

		return $ids;
	} else {

		return false;
	}
}
