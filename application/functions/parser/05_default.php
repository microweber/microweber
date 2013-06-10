<?php

if ($layout != '') {
	$mw_found_elems = '';
	$mw_found_elems_arr = array();

	$mw_to_cache = array('orig', $layout);
$cached = false;
	$parser_mem_crc = 'parser_' . crc32($layout) . CONTENT_ID;
	// d($parser_mem_crc);

	$do_not_cache = false;
	if (template_var('content') != false) {

		$do_not_cache = true;
		$cached = false;
	} else {
		//$cached = cache_get_content($parser_mem_crc, 'content_fields/global/parser');
		$cached = false;
	}

	//$cached = false;
	//
	if (isset($_REQUEST['debug'])) {
		//$cached = false;
	}

	//
	$ch = mw_var($parser_mem_crc);
	if ($cached != false) {
		$mw_to_cache = $cached;

	} else if ($ch != false) {

		$layout = $ch;
	} else {
		require_once (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . 'phpQuery.php');

		$layout = html_entity_decode($layout, ENT_COMPAT, "UTF-8");
		$layout = htmlspecialchars_decode($layout);

		$pq = phpQuery::newDocument($layout);
		$els = $pq['.edit'];
		//d($ch);
		//$els = $pq ['.edit *[rel!=""]'];
		//d($els);
		//$els  = $pq ['.edit *[rel]'];
		//d($els2);
		//exit();
		// $els = pq('body')->find('.edit')->filter(':not(script)');

		foreach ($els as $elem) {

			//$els2 = pq($elem)->find('[rel]')->filter(':not(script)');
			//d($els2);
			// iteration returns PLAIN dom nodes, NOT phpQuery objects
			$tagName = $elem -> tagName;
			$name = pq($elem) -> attr('field');

			if (strval($name) == '') {
				//$name = pq($elem) -> attr('id');
			}

			if (strval($name) == '') {
				$name = pq($elem) -> attr('data-field');
			}

			// $fld_id = pq($elem)->attr('data-field-id');

			$rel = pq($elem) -> attr('rel');
			if ($rel == false) {
				$rel = pq($elem) -> attr('data-rel');
				if ($rel == false) {
					$rel = 'page';
				}
			}

			$option_group = pq($elem) -> attr('data-option_group');
			if ($option_group == false) {
				$option_group = 'editable_region';
			}
			$data_id = pq($elem) -> attr('data-id');
			if ($data_id == false) {
				$data_id = pq($elem) -> attr('rel-id');
			}

			$option_mod = pq($elem) -> attr('data-module');
			if ($option_mod == false) {
				$option_mod = pq($elem) -> attr('data-type');
			}
			if ($option_mod == false) {
				$option_mod = pq($elem) -> attr('type');
			}
			$name = trim($name);
			$get_global = false;
			//  $rel = 'page';
			$field = $name;
			$use_id_as_field = $name;

			if ($rel == 'global') {
				$get_global = true;
			} else {
				$get_global = false;
			}

			if ($rel == 'mod1111111111ule') {
				$get_global = true;
			}
			if ($get_global == false) {
				//  $rel = 'page';
			}

			$try_inherited = false;
			if ($rel == 'content') {

				if (!isset($data_id) or $data_id == false) {
					$data_id = CONTENT_ID;
				}

				$get_global = false;
				$data_id = intval($data_id);
				$data = get_content_by_id($data_id);

				//$data['custom_fields'] = get_custom_fields_for_content($data_id, 0, 'all');

			} else if ($rel == 'page') {

				if (!isset($data_id) or $data_id == false) {
					$data_id = PAGE_ID;
				}
				$data = get_page($data_id);

				//$data['custom_fields'] = get_custom_fields_for_content($data['id'], 0, 'all');
				$get_global = false;
			} else if ($rel == 'post') {
				$get_global = false;
				if (!isset($data_id) or $data_id == false) {
					$data_id = POST_ID;
				}
				$data = get_content_by_id($data_id);

			} else if ($rel == 'inherit') {
				$get_global = false;
				if (!isset($data_id) or $data_id == false) {
					$data_id = PAGE_ID;
				}

				$inh = content_get_inherited_parent($data_id);

				if ($inh != false and intval($inh) != 0) {

					$try_inherited = true;

					$data_id = $inh;
					$rel = 'content';
					$data = get_content_by_id($data_id);
				} else {
					$rel = 'content';
					$data = get_page($data_id);
					//d($data);
					//
				}

			} else if (isset($attr['post'])) {
				$get_global = false;
				$data = get_post($attr['post']);
				if ($data == false) {
					$data = get_page($attr['post']);
					//$data['custom_fields'] = get_custom_fields_for_content($data['id'], 0, 'all');
				}
			} else if (isset($attr['category'])) {
				$get_global = false;
				$data = get_category($attr['category']);
			} else if (isset($attr['global'])) {
				$get_global = true;
			}
			$cf = false;
			$field_content = false;

			if (isset($data[$field])) {
				if (isset($data[$field])) {

					$field_content = $data[$field];

				}
			} else {
				if ($rel == 'page') {
					$rel = 'content';
				}
				if ($rel == 'post') {
					$rel = 'content';
				}
				$cont_field = false;

				//template_var

				if (isset($data_id) and $data_id != 0 and trim($data_id) != '' and trim($field) != '') {
					//

					$cont_field = get_content_field("rel={$rel}&field={$field}&rel_id=$data_id");
					// and $rel == 'inherit'
					if ($cont_field == false and $try_inherited == true) {

						$inh = content_get_inherited_parent($data_id);
						//d($data_id . $field . $inh);
						//
						if ($inh != false and intval($inh) != 0 and $inh != $data_id) {
							$data_id = $inh;

							$cont_field2 = get_content_field("rel={$rel}&field={$field}&rel_id=$inh");
							if ($cont_field2 != false) {
								$rel = 'content';
								$data = get_content_by_id($inh);

								$cont_field = $cont_field2;
							}
						}
					}

				} else {

					if (isset($data_id) and trim($data_id) != '' and $field_content == false and isset($rel) and isset($field) and trim($field) != '') {
						$cont_field = get_content_field("rel={$rel}&field={$field}&rel_id=$data_id");

						if ($cont_field != false) {
							$field_content = $cont_field;
						}
					} else {

						$cont_field = get_content_field("rel={$rel}&field={$field}");
 
					}
					if ($cont_field != false) {
						$cont_field = htmlspecialchars_decode(html_entity_decode($cont_field, ENT_COMPAT, "UTF-8"));
					}

				}
				if ($cont_field != false) {
					
				
					 $field_content = $cont_field;
				}
			}

			if ($field_content == false) {
				if ($get_global == true) {

					$cont_field = get_content_field("rel={$rel}&field={$field}");

					
					//dbg($cont_field);
					if ($cont_field == false) {
						if ($option_mod != false) {
							//$field_content = __FILE__ . __LINE__;
							//$field_content = get_option($field, $option_group, $return_full = false, $orderby = false);
							$field_content = get_content_field("rel={$option_group}&field={$field}");

							//
						} else {
							$field_content = get_content_field("rel={$option_group}&field={$field}");
 
							//$field_content = __FILE__ . __LINE__;
							//$field_content = get_option($field, $option_group, $return_full = false, $orderby = false);
						}
						
					} else {
						$field_content = $cont_field;
					}
					
					
					
					
					
					
				} else {





					if ($use_id_as_field != false) {
						if (isset($data[$use_id_as_field])) {
							$field_content = $data[$use_id_as_field];
							
						}
						/*
						 if ($field_content == false) {
						 if (isset($data['custom_fields'][$use_id_as_field])) {
						 $field_content = $data['custom_fields'][$use_id_as_field];
						 }
						 // d($field_content);
						 }*/

					}

					//  if ($field_content == false) {
					if (isset($data[$field])) {

						$field_content = $data[$field];

					} else {
						/*
						 if(!isset($data_id) or ($data_id) == false){
						 $data_id = 0;
						 }

						 $cont_field = get_content_field("rel=content&rel_id={$data_id}&field={$field}");
						 d($data_id);
						 d($field);
						 d($cont_field);
						 if ($cont_field != false) {
						 d($cont_field);

						 }*/

					}
					//}
				}

				if ($field == 'content' and template_var('content') != false) {
					$field_content = template_var('content');
						
					template_var('content', false);
				}




				if (isset($data_id) and trim($data_id) != '' and $field_content == false and isset($rel) and isset($field) and trim($field) != '') {
					$cont_field = get_content_field("rel={$rel}&field={$field}&rel_id=$data_id");

					if ($cont_field != false) {
						$field_content = $cont_field;
					}
				

				} else if ($field_content == false and isset($rel) and isset($field) and trim($field) != '') {
					$cont_field = get_content_field("rel={$rel}&field={$field}");

					if ($cont_field != false) {
						$field_content = $cont_field;
					}

				}
				if ($field_content == false and isset($data['custom_fields']) and !empty($data['custom_fields'])) {
					foreach ($data ['custom_fields'] as $kf => $vf) {

						if ($kf == $field) {

							//$field_content = ($vf);
						}
					}
				}
			}

			if ($field_content != false and $field_content != '') {
				
					$field_content = htmlspecialchars_decode(html_entity_decode($field_content, ENT_COMPAT, "UTF-8")); 
					 
				
				//$field_content = htmlspecialchars_decode($field_content);

				//$field_content = html_entity_decode($field_content, ENT_COMPAT, "UTF-8");
				// pecata d($field_content);
				//d($field_content);
				$parser_mem_crc2 = 'parser_field_content_' . crc32($field_content);

				$ch2 = mw_var($parser_mem_crc);

				/*
				 if ($use_apc == true) {

				 $cache_id_apc = $parser_mem_crc2;

				 $apc_field_content = apc_fetch($cache_id_apc);

				 if ($apc_field_content != false) {
				 $ch2 = true;
				 $field_content = $apc_field_content;
				 //	d($field_content);
				 pq($elem) -> html($field_content);
				 }
				 }*/

				if ($ch2 == false) {
					//$field_content = parse_micrwober_tags($field_content, $options, $coming_from_parent, $coming_from_parent_id);
					if ($field_content != false and $field_content != '') {
						$mw_found_elems = ',' . $parser_mem_crc2;
						//$field_content = htmlspecialchars_decode(html_entity_decode($field_content, ENT_COMPAT, "UTF-8")); 
						$mw_found_elems_arr[$parser_mem_crc2] = $field_content;
						 
						//pq($elem) -> html('<!--mw_replace_back_this_editable_' . $parser_mem_crc2.'-->');
						pq($elem) -> html('mw_replace_back_this_editable_' . $parser_mem_crc2 . '');

					}
					/*
					 if ($use_apc == true) {
					 @apc_store($cache_id_apc, $field_content, APC_EXPIRES);
					 }*/

				}
				mw_var($parser_mem_crc2, 1);

			} else {

			}
		}
		$layout = $pq -> htmlOuter();
		$pq -> __destruct();
		$pq = null;

		unset($pq);
		//if(strstr($haystack, $needle))
		mw_var($parser_mem_crc, $layout);
		if ($mw_found_elems != '') {
			$mw_to_cache['new'] = $layout;
			$mw_to_cache['to_replace'] = $mw_found_elems;
			$mw_to_cache['elems'] = $mw_found_elems_arr;
			//d($mw_to_cache);
			// $mw_to_cache = base64_encode(serialize($mw_to_cache));
			if ($do_not_cache == false) {

				//cache_save($mw_to_cache, $parser_mem_crc, 'content_fields/global/parser');
			}
		} else {
			$mw_to_cache['new'] = $layout;
 //as of beta
		 //cache_save($mw_to_cache, $parser_mem_crc, 'content_fields/global/parser');

		}
	}

}

if (isset($mw_to_cache) and !empty($mw_to_cache)) {

	if (isset($mw_to_cache['elems']) and isset($mw_to_cache['to_replace']) and isset($mw_to_cache['new'])) {

		$modified_layout = $mw_to_cache['new'];

		//$parser_mem_crc1 = 'parser_' . crc32($value['orig']);

		//$ch = mw_var($parser_mem_crc1);
		//$reps = explode(',', $mw_to_cache['to_replace']);

		//if ($ch == false) {
		$reps = $mw_to_cache['elems'];
		global $passed_reps;

		if ($passed_reps == NULL) {
			$passed_reps = array();

		}

		foreach ($reps as $elk => $value) {
			$elk_crc = crc32($elk);
			if (!in_array($elk_crc, $passed_reps)) {
				$passed_reps[] = $elk_crc;

				if ($value != '') {
					//$layout = $ch;
					$val_rep = $value;

					$val_rep = htmlspecialchars_decode($val_rep);

					//$options['no_cache'] = 1;
					$val_rep = parse_micrwober_tags($val_rep, $options, $coming_from_parent, $coming_from_parent_id);

					//$rep = '<!--mw_replace_back_this_editable_' . $elk.'-->';
					$rep = 'mw_replace_back_this_editable_' . $elk . '';
					//$modified_layout = $rep;
					//d($val_rep);
					$modified_layout = str_replace($rep, $val_rep, $modified_layout);
					//	mw_var($val_rep_parser_mem_crc, $modified_layout);

					//d($elk);
				}
			} else {
				//$passed_reps[] = $elk_crc;
				$rep = 'mw_replace_back_this_editable_' . $elk . '';
				//$modified_layout = $rep;

				$value = htmlspecialchars_decode($value);

				//$value = parse_micrwober_tags($value, $options, $coming_from_parent, $coming_from_parent_id);
				$modified_layout = str_replace($rep, $value, $modified_layout);
			}
		}
		// d($modified_layout);
		$layout = $modified_layout;
		$layout = html_entity_decode($layout, ENT_COMPAT, "UTF-8");
		$layout = htmlspecialchars_decode($layout);
		//}
	} elseif (isset($mw_to_cache['new'])) {
		//d($layout);
		//$layout = $mw_to_cache['new'];
		//d($layout);
		//$modified_layout = $mw_to_cache['orig'];
	}
	//
}
