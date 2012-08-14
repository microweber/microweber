<?php

/**
 * category_tree
 *
 * @desc prints category_tree of UL and LI
 * @access      public
 * @category    categories
 * @author      Microweber
 * @link        http://microweber.info/documentation/get_categories
 * @param
 $params = array();
 $params['content_parent'] = false; //parent id
 $params['link'] = false; // the link on for the <a href
 $params['actve_ids'] = array(); //ids of active categories
 $params['active_code'] = false; //inserts this code for the active ids's
 $params['remove_ids'] = array(); //remove those caregory ids
 $params['ul_class_name'] = false; //class name for the ul
 $params['include_first'] = false; //if true it will include the main parent category
 $params['content_type'] = false; //if this is set it will include only categories from desired type
 $params['add_ids'] = array(); //if you send array of ids it will add them to the category
 $params['orderby'] = array(); //you can order by such array $params['orderby'] = array('created_on','asc');

 */
function category_tree($params) {
	
	print 'category_tree()';
	return true;
	$p2 = array ();
	// p($params);
	if (! is_array ( $params )) {
		if (is_string ( $params )) {
			parse_str ( $params, $p2 );
			$params = $p2;
		}
	}
	if (isset ( $params ['content_parent'] )) {
		$content_parent = ($params ['content_parent']);
	} else if (isset ( $params ['content_subtype_value'] )) {
		$content_parent = ($params ['content_subtype_value']);
	} else {
		$content_parent = 0;
	}
	
	$link = isset ( $params ['link'] ) ? $params ['link'] : false;
	
	if ($link == false) {
		$link = "<a href='{taxonomy_url}' {active_code} >{taxonomy_value}</a>";
	}
	
	$actve_ids = isset ( $params ['actve_ids'] ) ? $params ['actve_ids'] : array (
			CATEGORY_ID 
	);
	$active_code = ($params ['active_code']) ? $params ['active_code'] : " class='active' ";
	$remove_ids = ($params ['remove_ids']) ? $params ['remove_ids'] : false;
	$removed_ids_code = ($params ['removed_ids_code']) ? $params ['removed_ids_code'] : false;
	if ($params ['class']) {
		$ul_class_name = $params ['class'];
	} else {
		$ul_class_name = ($params ['ul_class_name']) ? $params ['ul_class_name'] : $params ['ul_class_name'];
	}
	
	if ($params ['ul_class']) {
		$ul_class_name = $params ['ul_class'];
	}
	
	$include_first = ($params ['include_first']) ? $params ['include_first'] : false;
	$content_type = ($params ['content_type']) ? $params ['content_type'] : false;
	$add_ids = ($params ['add_ids']) ? $params ['add_ids'] : false;
	$orderby = ($params ['orderby']) ? $params ['orderby'] : false;
	
	if ($params ['for_page'] != false) {
		$page = get_page ( $params ['for_page'] );
		$content_parent = $page ['content_subtype_value'];
	}
	if ($params ['content_subtype_value'] != false) {
		$content_parent = $params ['content_subtype_value'];
	}
	
	if ($params ['not_for_page'] != false) {
		$page = get_page ( $params ['not_for_page'] );
		$remove_ids = array (
				$page ['content_subtype_value'] 
		);
	}
	
	content_helpers_getCaregoriesUlTree ( $content_parent, $link, $actve_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, $include_first, $content_type, $add_ids, $orderby, $only_with_content );
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
function content_helpers_getCaregoriesUlTree($content_parent, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $orderby = false, $only_with_content = false, $visible_on_frontend = false) {
	$table = c ( 'db_tables' );
	
	$table_content = $table ['table_content'];
	
	$table = $table_taxonomy = $table ['table_taxonomy'];
	
	if ($content_parent == false) {
		
		$content_parent =  ( 0 );
		
		$include_first = false;
	} else {
		
		$content_parent = (int) $content_parent;
	}
	
	if (! is_array ( $orderby )) {
		
		$orderby [0] = 'position';
		
		$orderby [1] = 'ASC';
	}
	
	if (! empty ( $remove_ids )) {
		
		$remove_ids_q = implode ( ',', $remove_ids );
		
		$remove_ids_q = " and id not in ($remove_ids_q) ";
	} else {
		
		$remove_ids_q = false;
	}
	
	if (! empty ( $add_ids )) {
		
		$add_ids_q = implode ( ',', $add_ids );
		
		$add_ids_q = " and id in ($add_ids_q) ";
	} else {
		
		$add_ids_q = false;
	}
	
	$content_type = addslashes ( $content_type );
	
	$inf_loop_fix = "  and $table.id<>$table.parent_id  ";
	
	if ($content_type == false) {
		
		if ($include_first == true) {
			
			$sql = "SELECT * from $table where id=$content_parent  and taxonomy_type='category'   $remove_ids_q $add_ids_q $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}  ";
		} else {
			
			$sql = "SELECT * from $table where parent_id=$content_parent and taxonomy_type='category'   $remove_ids_q $add_ids_q $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}   ";
		}
	} else {
		
		if ($include_first == true) {
			
			$sql = "SELECT * from $table where id=$content_parent and (taxonomy_content_type='$content_type' or taxonomy_content_type='inherit' )  $remove_ids_q $add_ids_q   $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}  ";
		} else {
			
			$sql = "SELECT * from $table where parent_id=$content_parent and taxonomy_type='category' and (taxonomy_content_type='$content_type' or taxonomy_content_type='inherit' )   $remove_ids_q  $add_ids_q $inf_loop_fix group by id order by {$orderby [0]}  {$orderby [1]}   ";
		}
	}
	
	if (empty ( $limit )) {
		$limit = array (
				0,
				10 
		);
	}
	
	if (! empty ( $limit )) {
		
		$my_offset = $limit [1] - $limit [0];
		
		$my_limit_q = " limit  {$limit[0]} , $my_offset  ";
	} else {
		
		$my_limit_q = false;
	}
	
	$children_of_the_main_parent = get_category_items ( $content_parent, $type = 'category_item', $visible_on_frontend, $limit );
	//
	$q = db_query ( $sql, $cache_id = 'content_helpers_getCaregoriesUlTree_parent_cats_q_' . md5 ( $sql ), 'taxonomy/' . intval ( $content_parent ) );
	// $q = $this->core_model->dbQuery ( $sql, $cache_id =
	// 'content_helpers_getCaregoriesUlTree_parent_cats_q_' . md5 ( $sql ),
	// 'taxonomy/' . intval ( $content_parent ) );
	
	$result = $q;
	
	//
	
	$only_with_content2 = $only_with_content;
	
	$do_not_show_next = false;
	
	$chosen_categories_array = array ();
	
	if (! empty ( $result )) {
		
		// $output = "<ul>";
		
		$i = 0;
		
		foreach ( $result as $item ) {
			
			$do_not_show = false;
			
			if ($only_with_content == true) {
				
				$check_in_table_content = false;
				
				if (is_array ( $only_with_content ) and ! empty ( $only_with_content )) {
					
					$content_ids_of_the_1_parent = $this->taxonomy_model->getToTableIds ( $only_with_content [0], $limit );
					
					$content_ids_of_the_1_parent_o = $content_ids_of_the_1_parent;
					
					if (! empty ( $content_ids_of_the_1_parent )) {
						
						$only_with_content3 = array ();
						
						$chosen_categories_array = array ();
						
						foreach ( $only_with_content2 as $only_with_content2_i ) {
							
							$only_with_content2_i = str_ireplace ( '{id}', $item ['id'], $only_with_content2_i );
							
							$only_with_content2_i = intval ( $only_with_content2_i );
							
							$chosen_categories_array [] = $only_with_content2_i;
						}
						
						if (count ( $chosen_categories_array ) > 1) {
							
							// array_shift ( $chosen_categories_array );
						}
						
						$chosen_categories_array_i = implode ( ',', $chosen_categories_array );
						
						$children_of_the_next_parent = get_category_items_ids ( $only_with_content [0], $limit );
						
						$children_of_the_next_parent_i = implode ( ',', $children_of_the_next_parent );
						
						$children_of_the_next_parent_qty = count ( $chosen_categories_array );
						
						$q = "
						select id , count(to_table_id) as qty from $table_taxonomy where
						to_table_id in($children_of_the_next_parent_i)
						and parent_id  IN ($chosen_categories_array_i)
						and taxonomy_type =  'category_item'
						group by to_table_id
						$my_limit_q
						";
						
						$total_count_array = array ();
						
						if (! empty ( $q )) {
							
							foreach ( $q as $q1 ) {
								$qty = intval ( $q1 ['qty'] );
								
								if (($children_of_the_next_parent_qty) == $qty) {
									$total_count_array [] = $q1;
								}
							}
						}
						
						$results_count = count ( $total_count_array );
						
						$content_ids_of_the_1_parent_i = implode ( ',', $children_of_the_main_parent );
						
						$chosen_categories_array_i = implode ( ',', $chosen_categories_array );
						
						$result [$i] ['content_count'] = $results_count;
						
						$item ['content_count'] = $results_count;
					} else {
						
						$results_count = 0;
					}
					
					$result [$i] ['content_count'] = $results_count;
					
					$item ['content_count'] = $results_count;
				} else {
					
					$do_not_show = false;
				}
			}
			
			$i ++;
		}
		
		if ($do_not_show == false) {
			
			$print1 = false;
			
			if ($ul_class_name == false) {
				
				$print1 = "<ul  class='category_tree'>";
			} else {
				
				$print1 = "<ul class='$ul_class_name'>";
			}
			
			print $print1;
			// print($type);
			foreach ( $result as $item ) {
				
				if ($only_with_content == true) {
					
					$do_not_show = false;
					
					$check_in_table_content = false;
					$childern_content = array ();
					
					$do_not_show = false;
					// print($type);
					
					if (! empty ( $childern_content )) {
						
						$do_not_show = false;
					} else {
						
						$do_not_show = true;
					}
				} else {
					
					$do_not_show = false;
				}
				
				if ($do_not_show == false) {
					
					$output = $output . $item ['taxonomy_value'];
					
					if ($li_class_name == false) {
						
						print "<li class='category_element' id='category_item_{$item['id']}'>";
					} else {
						
						print "<li class='$li_class_name' id='category_item_{$item['id']}' >";
					}
				}
				
				if ($do_not_show == false) {
					
					if ($link != false) {
						
						$to_print = false;
						
						$to_print = str_ireplace ( '{id}', $item ['id'], $link );
						
						$to_print = str_ireplace ( '{taxonomy_url}', $this->taxonomy_model->getUrlForIdAndCache ( $item ['id'] ), $to_print );
						
						$to_print = str_ireplace ( '{taxonomy_value}', $item ['taxonomy_value'], $to_print );
						
						$to_print = str_ireplace ( '{taxonomy_value2}', $item ['taxonomy_value2'], $to_print );
						
						$to_print = str_ireplace ( '{taxonomy_value3}', $item ['taxonomy_value3'], $to_print );
						
						$to_print = str_ireplace ( '{taxonomy_content_type}', trim ( $item ['taxonomy_content_type'] ), $to_print );
						
						$to_print = str_ireplace ( '{content_count}', $item ['content_count'], $to_print );
						
						if (intval ( $item ['content_count'] ) == 0) {
							
							$to_print = str_ireplace ( '{empty_or_full}', 'empty', $to_print );
						} else {
							
							$to_print = str_ireplace ( '{empty_or_full}', 'full', $to_print );
						}
						
						// print $item ['content_count'];
						
						if (is_array ( $actve_ids ) == true) {
							
							if (in_array ( $item ['id'], $actve_ids )) {
								
								$to_print = str_ireplace ( '{active_code}', $active_code, $to_print );
							} else {
								
								$to_print = str_ireplace ( '{active_code}', '', $to_print );
							}
						} else {
							
							$to_print = str_ireplace ( '{active_code}', '', $to_print );
						}
						
						if (is_array ( $remove_ids ) == true) {
							
							if (in_array ( $item ['id'], $remove_ids )) {
								
								if ($removed_ids_code == false) {
									
									$to_print = false;
								} else {
									
									$to_print = str_ireplace ( '{removed_ids_code}', $removed_ids_code, $to_print );
								}
							} else {
								
								$to_print = str_ireplace ( '{removed_ids_code}', '', $to_print );
							}
						}
						
						if (strval ( $to_print ) == '') {
							
							print $item ['taxonomy_value'];
						} else {
							
							print $to_print;
						}
					} else {
						
						print $item ['taxonomy_value'];
					}
					
					// $content_parent, $link = false, $actve_ids = false,
					// $active_code = false, $remove_ids = false,
					// $removed_ids_code = false, $ul_class_name = false,
					// $include_first = false, $content_type = false,
					// $li_class_name = false) {
					
					// $li_class_name = false, $add_ids = false, $orderby =
					// false, $only_with_content = false
					
					$children_of_the_main_parent1 = $this->taxonomy_model->getChildrensRecursive ( $item ['id'], $type = 'category', $visible_on_frontend = false );
					// p($children_of_the_main_parent1 );
					$remove_ids [] = $item ['id'];
					if (! empty ( $children_of_the_main_parent1 )) {
						foreach ( $children_of_the_main_parent1 as $children_of_the_main_par ) {
							
							// $remove_ids[] = $children_of_the_main_par;
							// $children = CI::model ( 'content'
							// )->content_helpers_getCaregoriesUlTree (
							// $children_of_the_main_par, $link, $actve_ids,
							// $active_code, $remove_ids, $removed_ids_code,
							// $ul_class_name, false, $content_type,
							// $li_class_name, $add_ids, $orderby,
							// $only_with_content, $visible_on_frontend );
						}
					}
					
					$children = $this->content_model->content_helpers_getCaregoriesUlTree ( $item ['id'], $link, $actve_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, false, $content_type, $li_class_name, $add_ids, $orderby, $only_with_content, $visible_on_frontend );
					
					print "</li>";
				}
			}
			
			print "</ul>";
		}
	} else {
	}
}
function get_category_items($parent_id, $type = false, $visible_on_frontend = false, $limit = false) {
	global $cms_db_tables;
	$taxonomy_id = intval ( $parent_id );
	
	$cache_group = 'taxonomy/' . $taxonomy_id;
	
	$tables = c ( 'db_tables' );
	
	$table = $tables ['table_taxonomy'];
	$table_items = $tables ['table_taxonomy_items'];
	
	$table_content = $tables ['table_content'];
	
	if ($orderby == false) {
		
		$orderby [0] = 'updated_on';
		
		$orderby [1] = 'DESC';
	}
	
	if (intval ( $parent_id ) == 0) {
		
		return false;
	}
	
	if (! empty ( $limit )) {
		
		$my_offset = $limit [1] - $limit [0];
		
		$my_limit_q = " limit  {$limit[0]} , $my_offset  ";
	} else {
		
		$my_limit_q = false;
	}
	
	$data = array ();
	
	$data ['parent_id'] = $parent_id;
	
	if ($type != FALSE) {
		// var_Dump($type);
		
		$data ['taxonomy_type'] = $type;
		
		$type_q = " and taxonomy_type='$type'   ";
	} else {
		// @doto: remove the hard coded part here by revieweing all the other
		// files for diferent values of $type
		$type = 'category_item';
		
		// var_Dump($type);
		
		$data ['taxonomy_type'] = $type;
		
		$type_q = " and taxonomy_type='$type'   ";
	}
	
	if ($visible_on_frontend == true) {
		
		$visible_on_frontend_q = " and to_table_id in (select id from $table_content where visible_on_frontend='y') ";
	}
	
	// $save = $this->taxonomyGet ( $data = $data, $orderby = $orderby );
	
	$cache_group = 'taxonomy/' . $parent_id;
	$q = " SELECT id,    parent_id from $table_items where parent_id= $parent_id   $type_q  $visible_on_frontend_q $my_limit_q ";
	// var_dump($q);
	$q_cache_id = __FUNCTION__ . crc32 ( $q );
	// var_dump($q_cache_id);
	$save = db_query ( $q, $q_cache_id, $cache_group );
	
	// $save = $this->getSingleItem ( $parent_id );
	if (empty ( $save )) {
		return false;
	}
	$to_return = array ();
	if (! empty ( $save )) {
		$to_return [] = $parent_id;
	}
	foreach ( $save as $item ) {
		$to_return [] = $item ['id'];
		/*
		 * $clidren = $this->getItemsRecursive ( $item ['id'], $type,
		 * $visible_on_frontend ); if (! empty ( $clidren )) { foreach (
		 * $clidren as $temp ) { $to_return [] = $temp; } }
		 */
	}
	
	$to_return = array_unique ( $to_return );
	
	return $to_return;
}
function get_category_items_ids($root, $limit = false) {
	if (! is_array ( $root )) {
		$root = intval ( $root );
		if (intval ( $root ) == 0) {
			
			return false;
		}
	}
	
	$cms_db_tables = c ( 'db_tables' );
	
	$table = $cms_db_tables ['table_taxonomy'];
	$table_taxonomy_items = $cms_db_tables ['table_taxonomy_items'];
	
	$table_content = $cms_db_tables ['table_content'];
	
	$ids = array ();
	
	if (! empty ( $limit )) {
		
		$my_offset = $limit [1] - $limit [0];
		
		$my_limit_q = " limit  {$limit[0]} , $my_offset  ";
	} else {
		
		$my_limit_q = " limit  0 , 500  ";
	}
	
	$data = array ();
	
	$data ['parent_id'] = $root;
	if (! is_array ( $root )) {
		$root_q = " parent_id=$root ";
		$cache_group = 'taxonomy/' . $root;
	} else {
		$root_i = implode ( ',', $root );
		$root_q = " parent_id in ($root_i) ";
		$cache_group = 'taxonomy/global';
	}
	
	$q = " SELECT id, parent_id,to_table_id from $table_taxonomy_items where $root_q $visible_on_frontend_q and taxonomy_type='category_item'  group by to_table_id   $my_limit_q ";
	
	// var_dump($q);
	$taxonomies = db_query ( $q, __FUNCTION__ . crc32 ( $q ), $cache_group );
	
	// var_dump($taxonomies);
	// print 'asds';;
	
	if (! empty ( $taxonomies )) {
		
		foreach ( $taxonomies as $item ) {
			
			if (intval ( $item ['to_table_id'] ) != 0) {
				
				$ids [] = $item ['to_table_id'];
			}
			
			/*
			 * if ($non_recursive == false) { $next = $this->getToTableIds (
			 * $item ['id'], $visible_on_frontend ); if (! empty ( $next )) {
			 * foreach ( $next as $n ) { if ($n != '') { $ids [] = $n; } } } }
			 */
		}
	}
	// p($ids);
	if (! empty ( $ids )) {
		
		$ids = array_unique ( $ids );
		
		asort ( $ids );
		
		return $ids;
	} else {
		
		return false;
	}
}


