<?php
function db_query($q, $cache_id = false, $cache_group = 'global', $time = false) {
	if (trim ( $q ) == '') {
		return false;
	}
	//$cache_id = false;
	if ($cache_id != false) {
		// $results =false;
		$results = cache_get_content ( $cache_id, $cache_group, $time );
		if ($results != false) {
			if ($results == '---empty---') {
				return false;
			} else {
				return $results;
			}
		}
	}
	$db = new DB ( c ( 'db' ) );
	$q = $db->get ( $q );
	if (empty ( $q )) {
		if ($cache_id != false) {
			
			cache_store_data ( '---empty---', $cache_id, $cache_group );
		}
		return false;
	}
	
	// $result = $q->result_array ();
	$result = $q;
	if ($cache_id != false) {
		if (! empty ( $result )) {
			cache_store_data ( $result, $cache_id, $cache_group );
		} else {
			cache_store_data ( '---empty---', $cache_id, $cache_group );
		}
	}
	
	return $result;
}

/**
 * get data from the database this is the MOST important function in the
 * Microweber CMS.
 * Everything relies on it.
 *
 * @author Peter Ivanov
 * @version 1.0
 * @since Version 1.0
 */
function db_get($table = false, $criteria = false, $limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false) {
	$cms_db_tables = c ( 'db_tables' ); // ->'table_options';
	                                    
	// $this->db->query ( 'SET NAMES utf8' );
	if ($table == false) {
		
		return false;
	}
	if (! empty ( $cms_db_tables )) {
		
		foreach ( $cms_db_tables as $k => $v ) {
			
			// var_dump($k, $v);
			if (strtolower ( $table ) == strtolower ( $v )) {
				
				$table_assoc_name = $k;
			}
		}
	}
	
	$aTable_assoc = db_get_table_name ( $table );
	
	if (! empty ( $criteria )) {
		if (isset ( $criteria ['debug'] )) {
			$debug = true;
			if (($criteria ['debug'])) {
				$criteria ['debug'] = false;
			} else {
				unset ( $criteria ['debug'] );
			}
		}
		if (isset ( $criteria ['cache_group'] )) {
			$cache_group = $criteria ['cache_group'];
		}
		if (isset ( $criteria ['no_cache'] )) {
			$cache_group = false;
			if (is_string ( $criteria ['no_cache'] )) {
				$criteria ['no_cache'] = false;
			} else {
				unset ( $criteria ['no_cache'] );
			}
		}
		
		if (isset ( $criteria ['count_only'] ) and $criteria ['count_only'] == true) {
			$count_only = $criteria ['count_only'];
			
			unset ( $criteria ['count_only'] );
		}
		
		if (isset ( $criteria ['count'] )) {
			$count_only = $criteria ['count'];
			
			unset ( $criteria ['count'] );
		}
		
		if (isset ( $criteria ['get_count'] ) and $criteria ['get_count'] == true) {
			$count_only = true;
			
			unset ( $criteria ['get_count'] );
		}
		
		if (isset ( $criteria ['count'] ) and $criteria ['count'] == true) {
			$count_only = $criteria ['count'];
			
			unset ( $criteria ['count'] );
		}
		
		if (isset ( $criteria ['with_pictures'] ) and $criteria ['with_pictures'] == true) {
			$with_pics = true;
		}
		
		if (isset ( $criteria ['limit'] ) and $criteria ['limit'] == true and $count_only == false) {
			$limit = $criteria ['limit'];
		}
		if (isset ( $criteria ['limit'] )) {
			$limit = $criteria ['limit'];
		}
		
		$curent_page = isset ( $criteria ['curent_page'] ) ? $criteria ['curent_page'] : null;
		if ($curent_page == false) {
			$curent_page = isset ( $criteria ['page'] ) ? $criteria ['page'] : null;
		}
		
		$offset = isset ( $criteria ['offset'] ) ? $criteria ['offset'] : false;
		
		if ($limit == false) {
			$limit = isset ( $criteria ['limit'] ) ? $criteria ['limit'] : false;
		}
		if ($offset == false) {
			$offset = isset ( $criteria ['offset'] ) ? $criteria ['offset'] : false;
		}
		
		if ($count_only == false) {
			
			if ($limit == false) {
				
				$qLimit = "";
				
				if (! isset ( $items_per_page ) or $items_per_page == false) {
					
					$items_per_page = 30;
				}
				
				$items_per_page = intval ( $items_per_page );
				
				if (intval ( $curent_page ) < 1) {
					
					$curent_page = 1;
				}
				
				$page_start = ($curent_page - 1) * $items_per_page;
				
				$page_end = ($page_start) + $items_per_page;
				
				$temp = $page_end - $page_start;
				
				if (intval ( $temp ) == 0) {
					
					$temp = 1;
				}
				
				$qLimit .= "LIMIT {$temp} ";
				
				if (($offset) == false) {
					
					$qLimit .= "OFFSET {$page_start} ";
				}
			}
			$limit_from_paging_q = $qLimit;
		}
		
		if ($debug) {
			// p($limit_from_paging_q);
			// p($limit);
		}
		
		if (isset ( $criteria ['fields'] )) {
			$only_those_fields = $criteria ['fields'];
			if (is_string ( $criteria ['fields'] )) {
				$criteria ['fields'] = false;
			} else {
				unset ( $criteria ['fields'] );
			}
		}
	}
	if (! empty ( $criteria )) {
		foreach ( $criteria as $fk => $fv ) {
			if (strstr ( $fk, 'custom_field_' ) == true) {
				
				$addcf = str_ireplace ( 'custom_field_', '', $fk );
				
				// $criteria ['custom_fields_criteria'] [] = array ($addcf =>
				// $fv );
				
				$criteria ['custom_fields_criteria'] [$addcf] = $fv;
			}
		}
	}
	if (! empty ( $criteria ['custom_fields_criteria'] )) {
		
		$table_custom_fields = $cms_db_tables ['table_custom_fields'];
		
		$only_custom_fieldd_ids = array ();
		
		$use_fetch_db_data = true;
		
		$ids_q = "";
		
		if (! empty ( $ids )) {
			
			$ids_i = implode ( ',', $ids );
			
			$ids_q = " and to_table_id in ($ids_i) ";
		}
		
		$only_custom_fieldd_ids = array ();
		// p($data ['custom_fields_criteria'],1);
		foreach ( $criteria ['custom_fields_criteria'] as $k => $v ) {
			
			if (is_array ( $v ) == false) {
				
				$v = addslashes ( $v );
				$v = html_entity_decode ( $v );
				$v = urldecode ( $v );
			}
			$is_not_null = false;
			if ($v == 'IS NOT NULL') {
				$is_not_null = true;
			}
			
			$k = addslashes ( $k );
			
			if (! empty ( $category_content_ids )) {
				
				$category_ids_q = implode ( ',', $category_content_ids );
				
				$category_ids_q = " and to_table_id in ($category_ids_q) ";
			} else {
				
				$category_ids_q = false;
			}
			
			$only_custom_fieldd_ids_q = false;
			
			if (! empty ( $only_custom_fieldd_ids )) {
				
				$only_custom_fieldd_ids_i = implode ( ',', $only_custom_fieldd_ids );
				
				$only_custom_fieldd_ids_q = " and to_table_id in ($only_custom_fieldd_ids_i) ";
			}
			if ($is_not_null == true) {
				$cfvq = "custom_field_value IS NOT NULL  ";
			} else {
				$cfvq = "custom_field_value LIKE '$v'  ";
			}
			$q = "SELECT  to_table_id from $table_custom_fields where

			to_table = '$aTable_assoc' and

			custom_field_name = '$k' and

			$cfvq

			$ids_q   $only_custom_fieldd_ids_q


			$my_limit_q

			order by field_order asc

			";
			
			$q2 = $q;
			// p($q);
			$q = $this->core_model->dbQuery ( $q, md5 ( $q ), 'custom_fields' );
			//
			
			if (! empty ( $q )) {
				
				$ids_old = $ids;
				
				$ids = array ();
				
				foreach ( $q as $itm ) {
					
					$only_custom_fieldd_ids [] = $itm ['to_table_id'];
					
					// if(in_array($itm ['to_table_id'],$category_ids)==
					// false){
					
					$includeIds [] = $itm ['to_table_id'];
					
					// }
					
					//
				}
			} else {
				
				// $ids = array();
				
				$remove_all_ids = true;
				
				$includeIds = false;
				
				$includeIds [] = '0';
				
				$includeIds [] = 0;
			}
		}
	}
	
	$original_cache_group = $cache_group;
	
	if (! empty ( $criteria ['only_those_fields'] )) {
		
		$only_those_fields = $criteria ['only_those_fields'];
		
		// unset($criteria['only_those_fields']);
		// no unset xcause f cache
	}
	
	if (! empty ( $criteria ['include_taxonomy'] )) {
		
		$include_taxonomy = true;
	} else {
		
		$include_taxonomy = false;
	}
	
	if (! empty ( $criteria ['exclude_ids'] )) {
		
		$exclude_ids = $criteria ['exclude_ids'];
		
		// unset($criteria['only_those_fields']);
		// no unset xcause f cache
	}
	
	if (! empty ( $criteria ['ids'] )) {
		foreach ( $criteria ['ids'] as $itm ) {
			
			$includeIds [] = $itm;
		}
	}
	
	$to_search = false;
	
	if (isset ( $criteria ['keyword'] )) {
		if (! isset ( $criteria ['search_by_keyword'] ) or $criteria ['search_by_keyword'] == false) {
			$criteria ['search_by_keyword'] = $criteria ['keyword'];
		}
	}
	
	if (isset ( $criteria ['keywords'] )) {
		if (! isset ( $criteria ['search_by_keyword'] ) or $criteria ['search_by_keyword'] == false) {
			$criteria ['search_by_keyword'] = $criteria ['keywords'];
		}
	}
	
	if (isset ( $criteria ['search_keyword'] )) {
		if (! isset ( $criteria ['search_by_keyword'] ) or $criteria ['search_by_keyword'] == false) {
			$criteria ['search_by_keyword'] = $criteria ['search_keyword'];
		}
	}
	
	if (isset ( $criteria ['search_in_fields'] )) {
		if ($criteria ['search_by_keyword_in_fields'] == false) {
			$criteria ['search_by_keyword_in_fields'] = $criteria ['search_in_fields'];
		}
	}
	
	if (isset ( $criteria ['search_by_keyword'] ) and strval ( trim ( $criteria ['search_by_keyword'] ) ) != '') {
		
		$to_search = trim ( $criteria ['search_by_keyword'] );
		
		// p($to_search,1);
	}
	
	if (isset ( $criteria ['search_by_keyword_in_fields'] ) and is_array ( ($criteria ['search_by_keyword_in_fields']) )) {
		
		if (! empty ( $criteria ['search_by_keyword_in_fields'] )) {
			
			$to_search_in_those_fields = $criteria ['search_by_keyword_in_fields'];
		}
	}
	
	// if ($count_only == false) {
	// var_dump ( $cache_group );
	if ($cache_group != false) {
		
		$cache_group = trim ( $cache_group );
		
		// $start_time = mktime ();
		
		if ($force_cache_id != false) {
			
			$cache_id = $force_cache_id;
			
			$function_cache_id = $force_cache_id;
		} else {
			
			$function_cache_id = false;
			
			$args = func_get_args ();
			
			foreach ( $args as $k => $v ) {
				
				$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
			}
			
			$function_cache_id = __FUNCTION__ . crc32 ( $function_cache_id );
			
			$cache_id = $function_cache_id;
		}
		
		$original_cache_id = $cache_id;
		
		$cache_content = cache_get_content ( $original_cache_id, $original_cache_group );
		
		if ($cache_group == 'taxonomy') {
			
			//
		}
		
		if (($cache_content) != false) {
			
			if ($cache_content == '---empty---') {
				
				return false;
			}
			
			if ($count_only == true) {
				
				$ret = $cache_content [0] ['qty'];
				
				return $ret;
			} else {
				
				return $cache_content;
			}
		}
	}
	
	if (! empty ( $orderby )) {
		
		$order_by = " ORDER BY  {$orderby[0]}  {$orderby[1]}  ";
	} else {
		
		$order_by = false;
	}
	
	if ($qLimit == '' and ! empty ( $limit ) and $count_only == false) {
		
		$offset = $limit [1] - $limit [0];
		
		$limit = " limit  {$limit[0]} , $offset  ";
	} else {
		
		$limit = false;
	}
	
	$criteria = map_array_to_database_table ( $table, $criteria );
	
	if (! empty ( $criteria )) {
		
		// $query = $this->db->get_where ( $table, $criteria, $limit,
		// $offset );
	} else {
		
		// $query = $this->db->get ( $table, $limit, $offset );
	}
	
	if ($only_those_fields == false) {
		
		$q = "SELECT * FROM $table ";
	} else {
		
		if (is_array ( $only_those_fields )) {
			
			if (! empty ( $only_those_fields )) {
				
				$flds = implode ( ',', $only_those_fields );
				
				$q = "SELECT $flds FROM $table ";
			} else {
				
				$q = "SELECT * FROM $table ";
			}
		} else {
			
			$q = "SELECT * FROM $table ";
		}
	}
	
	if ($count_only == true) {
		
		$q = "SELECT count(*) as qty FROM $table ";
	}
	
	$where = false;
	
	if (is_array ( $ids )) {
		
		if (! empty ( $ids )) {
			
			$idds = false;
			
			foreach ( $ids as $id ) {
				
				$id = intval ( $id );
				
				$idds .= "   OR id=$id   ";
			}
			
			$idds = "  and ( id=0 $idds   ) ";
		} else {
			
			$idds = false;
		}
	}
	
	if (! empty ( $exclude_ids )) {
		
		$first = array_shift ( $exclude_ids );
		
		$exclude_idds = false;
		
		foreach ( $exclude_ids as $id ) {
			
			$id = intval ( $id );
			
			$exclude_idds .= "   AND id<>$id   ";
		}
		
		$exclude_idds = "  and ( id<>$first $exclude_idds   ) ";
	} else {
		
		$exclude_idds = false;
	}
	
	if (! empty ( $includeIds )) {
		
		// $first = array_shift ( $includeIds );
		
		$includeIds_idds = false;
		// p ( $includeIds );
		// p($includeIds);
		
		$includeIds_i = implode ( ',', $includeIds );
		
		$includeIds_idds .= "   AND id IN ($includeIds_i)   ";
	} else {
		
		$includeIds_idds = false;
	}
	
	if ($to_search != false) {
		
		$fieals = $this->dbGetTableFields ( $table );
		
		$where_post = ' OR ';
		
		if (! $where) {
			
			$where = " WHERE ";
		}
		$where_q = '';
		
		foreach ( $fieals as $v ) {
			
			$add_to_seachq_q = true;
			
			if (! empty ( $to_search_in_those_fields )) {
				
				if (in_array ( $v, $to_search_in_those_fields ) == false) {
					
					$add_to_seachq_q = false;
				}
			}
			
			if ($add_to_seachq_q == true) {
				
				if ($v != 'id' && $v != 'password') {
					
					// $where .= " $v like '%$to_search%' " . $where_post;
					
					$where_q .= " $v REGEXP '$to_search' " . $where_post;
					
					// 'new\\*.\\*line';
					
					// $where .= " MATCH($v) AGAINST ('*$to_search* in
					// boolean mode') " . $where_post;
				}
			}
		}
		
		$where_q = rtrim ( $where_q, ' OR ' );
		
		if ($includeIds_idds != false) {
			$where = $where . '  (' . $where_q . ')' . $includeIds_idds;
		} else {
			
			$where = $where . $where_q;
		}
	} else {
		
		if (! empty ( $criteria )) {
			
			if (! $where) {
				
				$where = " WHERE ";
			}
			foreach ( $criteria as $k => $v ) {
				$compare_sign = '=';
				if (stristr ( $v, '[lt]' )) {
					$compare_sign = '<=';
					$v = str_replace ( '[lt]', '', $v );
				}
				
				if (stristr ( $v, '[mt]' )) {
					
					$compare_sign = '>=';
					
					$v = str_replace ( '[mt]', '', $v );
				}
				/*
				 * var_dump ( $k ); var_dump ( $v ); print '<hr>';
				 */
				if (($k == 'updated_on') or ($k == 'created_on')) {
					
					$v = strtotime ( $v );
					$v = date ( "Y-m-d H:i:s", $v );
				}
				
				$where .= "$k {$compare_sign} '$v' AND ";
			}
			if ($table_assoc_name != 'table_comments') {
				if ($with_pics == true) {
					$table_media = $cms_db_tables ['table_media'];
					$where .= " id in (select to_table_id from $table_media where to_table='$table_assoc_name'   )     AND ";
				}
			}
			
			$where .= " ID is not null ";
		} else {
			
			$where = " WHERE ";
			
			$where .= " ID is not null ";
		}
	}
	
	if ($where != false) {
		
		$q = $q . $where . $idds . $exclude_idds;
	} else {
		$q = $q . " WHERE " . $idds . $exclude_idds;
	}
	if ($includeIds_idds != false) {
		$q = $q . $includeIds_idds;
	}
	if ($count_only != true) {
		$q .= " group by ID  ";
	}
	if ($order_by != false) {
		
		$q = $q . $order_by;
	}
	
	if (trim ( $limit_from_paging_q ) != "") {
		$limit = $limit_from_paging_q;
	} else {
	}
	if ($limit != false) {
		
		$q = $q . $limit;
	}
	
	if ($debug == true) {
		
		var_dump ( $table, $q );
	}
	
	$result = db_query ( $q );
	if ($count_only == true) {
		
		// var_dump ( $result );
		// exit ();
	}
	
	if ($result [0] ['qty'] == true) {
		
		// p($result);
		$ret = $result [0] ['qty'];
		
		return $ret;
	}
	
	if ($only_those_fields == false) {
		
		if ($count_only == false) {
			
			if (! empty ( $result )) {
				
				if (count ( $result ) < 2) {
					
					$table_custom_field = $cms_db_tables ['table_custom_fields'];
					
					if (strval ( $table_assoc_name ) != 'table_custom_fields') {
						
						if (strval ( trim ( $table_assoc_name ) ) != '') {
							
							if (strval ( $table_assoc_name ) == 'table_content') {
								
								$this_cache_id = __FUNCTION__ . 'custom_fields_stuff' . md5 ( serialize ( $result ) );
								
								$this_cache_content = $this->cacheGetContentAndDecode ( $this_cache_id );
								
								// $this_cache_content = false;
								if (($this_cache_content) != false) {
									
									$result = $this_cache_content;
								} else {
									
									$the_data_with_custom_field__stuff = array ();
									
									foreach ( $result as $item ) {
										
										if (strval ( $table_assoc_name ) != '') {
											
											if (intval ( $item ['id'] ) != 0) {
												
												$q = " SELECT
																* from  $table_custom_field where
																to_table = '$table_assoc_name'
																and to_table_id={$item['id']}

																order by field_order asc
																";
												
												// print $q;
												$cache_id = __FUNCTION__ . 'custom_fields_stuff' . md5 ( $q );
												
												$cache_id = md5 ( $cache_id );
												
												$q = $this->dbQuery ( $q );
												
												if (! empty ( $q )) {
													
													$append_this = array ();
													
													foreach ( $q as $q2 ) {
														
														$i = 0;
														
														$the_name = false;
														
														$the_val = false;
														
														foreach ( $q2 as $cfk => $cfv ) {
															
															if ($cfk == 'custom_field_name') {
																
																$the_name = $cfv;
															}
															
															if ($cfk == 'custom_field_value') {
																
																$the_val = $cfv;
															}
															
															$i ++;
														}
														
														if ($the_name != false and $the_val != false) {
															
															$append_this [$the_name] = $the_val;
														}
													}
													
													// var_dump (
													// $append_this );
													$item ['custom_fields'] = $append_this;
												}
											}
										}
										
										$the_data_with_custom_field__stuff [] = $item;
									}
									
									$result = $the_data_with_custom_field__stuff;
									
									// var_dump($result);
									$this->cacheWriteAndEncode ( $result, $this_cache_id, $cache_group = 'global' );
								}
							}
						}
					}
				}
			}
		}
	}
	
	// $result = $this->decodeLinksAndReplaceSiteUrl ( $result );
	
	if ($table_assoc_name != 'table_options') {
		
		// todo
		
		if ($get_only_whats_requested_without_additional_stuff == false) {
			
			if ($only_those_fields == false) {
				
				// print $table_assoc_name;
				if (strval ( $table_assoc_name ) != '') {
					
					if ($table_assoc_name != 'table_media') {
						
						$result_with_media = array ();
						
						if (! empty ( $result )) {
							
							if (count ( $result ) == 1) {
								
								foreach ( $result as $item ) {
									
									// get media
									if (intval ( $item ['id'] ) != 0) {
										
										$media = $this->mediaGetAndCache ( $table_assoc_name, $item ['id'] );
										
										if (! empty ( $media )) {
											
											$item ['media/global'] = $media;
										}
									}
									
									$result_with_media [] = $item;
								}
								
								$result = $result_with_media;
							}
						}
					}
				}
				
				if ($table_assoc_name != 'table_taxonomy') {
					
					// todo
				}
			}
		}
	}
	
	if ($cache_group != false) {
		
		if (! empty ( $result )) {
			
			// p($original_cache_group);
			// p($cache_id);
			cache_store_data ( $result, $original_cache_id, $original_cache_group );
		} else {
			
			cache_store_data ( '---empty---', $original_cache_id, $original_cache_group );
		}
	}
	
	// var_dump($result);
	if ($count_only == true) {
		
		$ret = $result [0] ['qty'];
		
		return $ret;
	}
	
	$return = array ();
	
	if (! empty ( $result )) {
		
		foreach ( $result as $k => $v ) {
			
			$v =remove_slashes_from_array ( $v );
			
			$return [$k] = $v;
		}
	}
	
	// var_dump ( $return );
	return $return;
}
function db_get_table_name($assoc_name) {
	$cms_db_tables = c ( 'db_tables' ); // ->'table_options';
	
	if (! empty ( $cms_db_tables )) {
		
		foreach ( $cms_db_tables as $k => $v ) {
			
			// var_dump($k, $v);
			if (strtolower ( $assoc_name ) == strtolower ( $v )) {
				
				// $table_assoc_name = $k;
				return $v;
			}
		}
		
		return $assoc_name;
	}
}

/**
 * returns array that contains only keys that has the same names as the
 * table fields from the database
 *
 * @param
 *        	string
 * @param
 *        	array
 * @return array
 * @author Peter Ivanov
 * @version 1.0
 * @since Version 1.0
 */
function map_array_to_database_table($table, $array) {
	if (empty ( $array )) {
		
		return false;
	}
	// $table = db_get_table_name($table);
	
	$fields = db_get_table_fields ( $table );
	
	foreach ( $fields as $field ) {
		
		$field = strtolower ( $field );
		
		if (array_key_exists ( $field, $array )) {
			
			if ($array [$field] != false) {
				
				// print ' ' . $field. ' <br>';
				$array_to_return [$field] = $array [$field];
			}
			
			if ($array [$field] == 0) {
				
				$array_to_return [$field] = $array [$field];
			}
		}
	}
	
	return $array_to_return;
}

/**
 * Gets all field names from a DB table
 *
 * @param $table string
 *        	- table name
 * @param $exclude_fields array
 *        	- fields to exclude
 * @return array
 * @author Peter Ivanov
 * @version 1.0
 * @since Version 1.0
 */
function db_get_table_fields($table, $exclude_fields = false) {
	if (! $table) {
		
		return false;
	}
	
	$function_cache_id = false;
	
	$args = func_get_args ();
	
	foreach ( $args as $k => $v ) {
		
		$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
	}
	
	$function_cache_id = __FUNCTION__ . crc32 ( $function_cache_id );
	
	$cache_content = cache_get_content ( $function_cache_id, 'db' );
	
	if (($cache_content) != false) {
		
		return $cache_content;
	}
	
	$table = db_get_table_name ( $table );
	
	$sql = "show columns from $table";
	
	// var_dump($sql );
	$query = db_query ( $sql );
	
	$fields = $query;
	
	$exisiting_fields = array ();
	
	foreach ( $fields as $fivesdraft ) {
		
		$fivesdraft = array_change_key_case ( $fivesdraft, CASE_LOWER );
		
		$exisiting_fields [strtolower ( $fivesdraft ['field'] )] = true;
	}
	
	// var_dump ( $exisiting_fields );
	$fields = array ();
	
	foreach ( $exisiting_fields as $k => $v ) {
		
		if (! empty ( $exclude_fields )) {
			
			if (in_array ( $k, $exclude_fields ) == false) {
				
				$fields [] = $k;
			}
		} else {
			
			$fields [] = $k;
		}
	}
	
	cache_store_data ( $fields, $function_cache_id, $cache_group = 'db' );
	
	// $fields = (array_change_key_case ( $fields, CASE_LOWER ));
	return $fields;
}