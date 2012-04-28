<?

/**
 * @desc  parses the mw tags
 * @param $layout - string
 * @param $options
 * $options ['admin'] - loads the module admin
 * @return string
 * @author	Microweber Dev Team
 * @version 1.0
 * @since Version 1.0
 */
function parse_micrwober_tags($layout, $options = false) {
global $CI ;
	if (!defined('PAGE_ID')) {

		if (intval(PAGE_ID) == 0) {

			$p = url($skip_ajax = false);
			$page = $CI-> content_model -> getPageByURLAndCache($p);

			define("PAGE_ID", $page['id']);

		}

	}

 	//	$this->core_model->cacheWriteAndEncode ( $layout, $function_cache_id, $cache_group );
 
	//echo memory_get_usage() . "\n"; // 36640
	/*$cache_id =  md5 ( $layout ) . md5 ( serialize ( $options ) );
	 $cache_group = 'blocks/'.DIRECTORY_SEPARATOR.intval(PAGE_ID).DIRECTORY_SEPARATOR.'';

	 */

	if (($cache_content) == false) {

		//return $cache_content;

		//	$cache_content = $this->core_model->cacheGetContentAndDecode ( $cache_id, $cache_group );

	}

	static $mem = array();
	static $mem2 = array();
	$layout_md5 = md5($layout);
	$options_md5 = md5(serialize($options));
	$check = $layout_md5 . $options_md5;
	if ($mem["{$check}"] != false) {
		return $mem[$check];
	}
	//var_dump( $this->$parse_memory);
	//$layout = str_ireplace ( '<mw', '<microweber', $layout );
	$layout = replace_in_long_text('<mw', '<microweber', $layout, $use_normal_replace = true);

	$v = $layout;

	//	$tags1 = $this->core_model->extractTags ( $v, '*', $selfclosing = true, $return_the_entire_tag = true, $charset = 'UTF-8' );

	if (strstr($layout, '<microweber') == true) {

		//$layout = $this->parse_micrwober_tags ( $layout, $options );

	}

	if (strstr($layout, '<mw') == true) {
		//$layout = $this->parse_micrwober_tags ( $layout, $options );
	}

	if (strstr($layout, '<nomw') == true) {
		$relations = array();
		$tags = $CI-> core_model -> extractTags($layout, 'nomw', $selfclosing = false, $return_the_entire_tag = true, $charset = 'UTF-8');
		//	p($tags);
		$matches = $tags;
		$txt_to_replace_back = array();
		if (!empty($matches)) {
			//
			foreach ($matches as $m) {

				$hash = md5($m['full_tag']);
				$hash = 'replace_back_' . $hash;

				$txt_to_replace_back[$hash] = $m['full_tag'];

				$layout = str_replace($m['full_tag'], $hash, $layout);

			}
		}
	}

	if (strstr($layout, '<microweber') == true) {

		$editmode = $CI-> core_model -> is_editmode();

		$relations = array();
		$tags = $CI-> core_model -> extractTags($layout, 'microweber', $selfclosing = true, $return_the_entire_tag = true, $charset = 'UTF-8');
		//	p($tags);
		$matches = $tags;
		if (!empty($matches)) {

			//
			foreach ($matches as $m) {

				if ($m['tag_name'] == 'microweber') {

					$attr = $m['attributes'];

					if ($attr['module'] != '') {

						$mmd5 = md5($attr['module']);
						$check_if_uninstalled = MODULES_DIR . '_system/' . $mmd5 . '.php';

						//if (is_dir ( MODULES_DIR . '_system/' ) == false) {
						//	//touch ( MODULES_DIR . '_system/' );
						//}
						if (is_file($check_if_uninstalled) == true) {
							//	$attr ['module'] = false;
						}
					}

					if (strval($attr['module']) == '') {
						$attr['module'] = 'non_existing';
					}

					if ($attr['module'] != '') {

						$attr['module'] = trim($attr['module']);
						$attr['module'] = str_replace('\\', '/', $attr['module']);
						$attr['module'] = reduce_double_slashes($attr['module']);

						$try_file1 = MODULES_DIR . '' . $attr['module'] . '.php';

						$try_file = MODULES_DIR . 'modules/' . $attr['module'] . '.php';

						$try_file_db_file = MODULES_DIR . $attr['module'] . '_db.php';
						$try_file_db_file = normalize_path($try_file_db_file, false);
						//p($try_file_db_file);
						if (is_file($try_file_db_file) == true) {
							//$this->init_model->db_setup_from_file ( $try_file_db_file );
						}

						if ($options['admin'] == true) {
							$try_file1 = MODULES_DIR . 'admin/' . $attr['module'] . '.php';
							$try_filefront = MODULES_DIR . '' . $attr['module'] . '.php';

							if (strstr($attr['module'], 'admin') == false) {

								$try_file1 = MODULES_DIR . 'admin/' . $attr['module'] . '.php';
							} else {
								$try_file1 = MODULES_DIR . '' . $attr['module'] . '.php';
							}

						}
						$try_file1 = normalize_path($try_file1, false);
						//$a = is_file ( $try_file1 );
						if ($attr['file']) {
							$attr['view'] = $attr['file'];
						}

						if ($attr['view']) {
							$module_view_file = ACTIVE_TEMPLATE_DIR . $attr['view'];
							$module_view_file = normalize_path($module_view_file, false);
							// p($module_view_file);
							$is_admin = is_admin();
							if (is_file($module_view_file) == false) {
								if ($is_admin == true) {

									$try_config_file = MODULES_DIR . '' . $attr['module'] . '_config.php';

									$dir = dirname($module_view_file);
									if (is_dir($dir) == false) {
										mkdir_recursive($dir);
									}

									if (copy($try_file1, $module_view_file)) {
										$try_file1 = $module_view_file;
									}

								}
							} else {
								$try_file1 = $module_view_file;
							}

						}

						if (is_file($try_file1) == false) {

							if (is_file($try_file) == true) {
								$try_config_file = DEFAULT_TEMPLATE_DIR . 'modules/' . $attr['module'] . '_config.php';
							} else {
								if ($options['admin'] == true) {
									if (strstr($attr['module'], 'admin') == false) {

										$try_file = MODULES_DIR . 'admin/' . '' . 'default' . '.php';
									} else {
										$try_file = MODULES_DIR . '' . '' . 'default' . '.php';
									}

								}
							}

							$is_admin = is_admin();

							if ($is_admin == true) {
								$try_file1 = $try_filefront;
								$try_config_file = MODULES_DIR . '' . $attr['module'] . '_config.php';

								$dir = dirname($try_file1);
								if (is_dir($dir) == false) {
									mkdir_recursive($dir);
								}

								//print ( "You are trying to call module that doesnt exist in $try_file1.$try_file Please create it!" );
								//var_dump( $try_file, $try_file1);
								//exit ( "Modile file not found in $try_file1. Please create it!" );
								//if (! copy ( $try_file, $try_file1 )) {
								//echo "failed to copy $file...\n";
								//}

								$error = true;
							} else {
								//print ( "You are trying to call module that doesnt exist in $try_file1.$try_file Please create it!" );
								$error = true;
							}
						} else {
							$try_config_file = MODULES_DIR . '' . $attr['module'] . '_config.php';

						}
						if ($error == true) {
							$try_file1 = MODULES_DIR . 'non_existing.php';
							$error = false;
						}
						//	p($try_file1);

						if (($attr['module_id']) == true) {
							$mod_id = $attr['module_id'];
						} else {
							//$mod_id = false;
							$mod_id = $attr['module'];
							$mod_id = str_replace('/', '_', $mod_id);
							$mod_id = str_replace('\\', '_', $mod_id);
							$mod_id = str_replace('-', '_', $mod_id);
							$attr['module_id'] = $mod_id;
						}

						if (is_file($try_file1) == true and $error == false) {
							$arrts = array();
							foreach ($attr as $att => $at) {
								// $CI-> template[$att] = ($at);
								//$this->template [$att] = mw_get_var($at);
								// $this->load-> vars(array($att => $at));
								$arrts[$att] = ($at);
							}
							$CI-> load -> vars($arrts);
							$no_edit = false;
							$no_admin = false;
							$check2 = false;
							$mem2_md5_1 = md5(serialize($m));
							//$mem2_md5_2 = md5 ( serialize ( $arrts ) );
							$temp2 = false;
							$check2 = $mem2_md5_1;
							$check2 = strval($check2);
							//
							//$temp1 = parse_memory_storage($check2);

							if ($temp1 != false) {
								//var_dump ( $temp1 );
								$module_file = $temp1;
							} else {
								//
								$try_config_file = normalize_path($try_config_file);
								$try_config_file = rtrim($try_config_file, '\\');

								$cache_this = true;
								$force_cache_this = false;

								//p($try_config_file);
								$config = false;

								if (!is_file($try_config_file)) {
									$try_config_file = str_replace('admin', '', $try_config_file);
									$try_config_file = ltrim($try_config_file, '\\');
								}

								if (is_file($try_config_file)) {

									include ($try_config_file);

									if (!empty($config)) {
										$check_icon = MODULES_DIR . '' . $attr['module'] . '.png';
										$icon = pathToURL($check_icon);
										//p($config);

										$config['icon'] = $icon;

										if (!empty($config['options'])) {
											$CI-> setup_module_options($config['options']);

										}
										$cache_for_session = false;
										if ($config['no_cache'] == true) {
											$cache_this = false;
											$do_not_cache_whole_block = true;
											$cache_for_session = true;
										}

										if ($config['cache'] == true) {
											$force_cache_this = true;

											$cache_for_session = true;
										}

										if ($config['no_edit'] == true) {
											$no_edit = true;

										}

										if ($config['no_admin'] == true) {
											$no_admin = true;

										}
									}

								}

								$config['url_to_module'] = (MODULES_DIR . '' . $attr['module'] . '.php');
								$config['path_to_module'] = normalize_path((dirname($config['url_to_module'])) . '/', true);
								$config['url_to_module'] = pathToURL(dirname($config['url_to_module'])) . '/';

								$config['path_to_module_front'] = normalize_path(str_ireplace('admin', '', $config['path_to_module']), true);
								$config['url_to_module_front'] = str_ireplace('admin', '', $config['url_to_module']);
								//p($config);
								// $CI-> template['config'] = $config;
								$CI-> load -> vars(array('config' => $config));

								if ($arrts['no_cache'] == true) {
									$cache_this = false;
								}

								if ($options['no_cache'] == true) {
									$cache_this = false;
								}
								if ($config['no_cache'] == true) {
									$cache_this = false;
								}
								$mod_title = '';
								if ($config['name'] == true) {
									$mod_title = $config['name'];
								}

								if ($force_cache_this == false) {
									if (strstr($attr['module'], 'admin/') == true) {
										$cache_this = false;

									}
								}

								if ($options['admin'] == true) {
									$cache_this = false;
								}
								$params_encoded = false;

								if ($force_cache_this == true) {
									$cache_this = true;
								}
								//var_dump($force_cache_this);

								$is_admin = is_admin();

								if ($arrt['view']) {

									$copy_to = TEMPLATE_DIR . $arrt['view'];
									if (is_file($try_file1) and !is_file($copy_to)) {
										if ($is_admin == true) {

											$src_to_copy = $try_file1;
											copy($src_to_copy, $copy_to);
											$try_file1 = $copy_to;
										}
									}

									if (is_file($copy_to)) {

										$try_file1 = $copy_to;

									}

								}

								if ($cache_this == true) {
									$cache_id = md5($try_file1) . md5(serialize($arrts));

									if ($cache_for_session == true) {
										$cache_id = md5($try_file1) . sess_id() . md5(serialize($arrts));
									}

									if ($_POST) {
										$cache_id = $cache_id . md5(serialize($_POST));
									}

									$cache_group = 'global/blocks/';

									//$cache_content = $this->core_model->cacheGetContentAndDecode ( $cache_id, $cache_group );

									if (($cache_content) != false) {
										//var_dump($cache_content);
										$module_file = $cache_content;

									} else {
										//	p($arrts);

										// $CI-> template['params'] = $arrts;

										$CI-> load -> vars(array('params' => $arrts));

										//$module_file = $this->load->file ( $try_file1, true );

										$module_file = $CI-> load -> file($try_file1, true);

										//$this->core_model->cacheWriteAndEncode ( $module_file, $cache_id, $cache_group );
									}

								} else {
									$CI-> load -> vars(array('params' => $arrts));

									// // $CI-> template['params'] = $arrts;

									//p($this->template);
									//$module_file = $this->load->file ( $try_file1, true );
									$module_file = $CI-> load -> file($try_file1, true);
								}
								//$params_encoded = encode_var ( $arrts );

								$params_encoded = 'edit_tag';
								$params_module = codeClean($arrts['module']);

							}
							//if (($attr ['module'] != 'header') and ($attr ['module'] != 'footer')) {

							//
							$more_attrs2 = '';
							if (!empty($arrts)) {

								foreach ($arrts as $arrts_k => $arrts_v) {

									if (strstr($arrts['module'], 'admin')) {
										if ((strtolower($arrts_k) != 'class') && (strtolower($arrts_k) != 'contenteditable') && (strtolower($arrts_k) != 'style')) {
											$more_attrs2 .= " {$arrts_k}='{$arrts_v}' ";
										} else {

										}
									} else {
										if ((strtolower($arrts_k) != 'class') && (strtolower($arrts_k) != 'contenteditable')) {
											$more_attrs2 .= " {$arrts_k}='{$arrts_v}' ";
										} else {

										}
									}

								}

							} else {

							}
							if ($mod_id == true) {
								$mod_id_tag = ' module_id="' . $mod_id . '"   name="' . $mod_id . '" ';
							} else {
								//	$mod_id_tag = '';
								$mod_id_tag = ' module_id="default"  name="default" ';

							}
							if ($mod_title != '') {
								$mod_id_tag .= ' module_title="' . $mod_title . '" ';
							}
							if ($editmode = true) {
								//	p($m);
								//p( $arrts);

								if ($no_admin == true) {
									$no_admin_tag = ' no_admin="true" ';
								} else {
									$no_admin_tag = '';
								}
								$params_module_clean = str_replace('/', '__', $params_module);
								$params_module_clean = str_replace('/', '-', $params_module_clean);
								$params_module_clean = str_replace(' ', '-', $params_module_clean);
								//$edtid_hash = base64_encode ( $m ['full_tag'] );
								$edtid_hash = 'edit_tag';

								$more_attrs = '';

								$more_attrs = " class='mercury-snippet module' ";

								$more_attrs2 .= " data____snippet='{$params_module_clean}|{$mod_id}'  data_version='1' ";

								$more_attrs2 .= " data-snippet='{$params_module_clean}|{$mod_id}'  contenteditable='false' ";

								//  p($more_attrs2);
								//
								//
								if (strval($module_file) != '') {

									if ($options['do_not_wrap'] == true) {
										$module_file = $module_file;

									} else {

										if ($no_edit == false) {
											//$module_file = '<div onmouseup="load_edit_module_by_module_id(\'' . $mod_id . '\')" mw_params_encoded="' . $params_encoded . '"  mw_params_module="' . $params_module . '"    ' . $mod_id_tag . ' class="module" ' . $no_admin_tag . ' edit="' . $edtid_hash . '">' . $module_file . '</div>';
											$module_file = '<div ' . $more_attrs . $more_attrs2 . ' mw_params_encoded="' . $params_encoded . '"  mw_params_module="' . $params_module . '"    ' . $mod_id_tag . '  ' . $no_admin_tag . ' edit="' . $edtid_hash . '">' . $module_file . '</div>';

											//$module_file = '<div mw_params_encoded="' . $params_encoded . '"  mw_params_module="' . $params_module . '"    ' . $mod_id_tag . ' class="module" ' . $no_admin_tag . ' edit="' . $edtid_hash . '">' . $module_file . '</div>';

										} else {
											$module_file = '<div  ' . $more_attrs . $more_attrs2 . ' mw_params_encoded="' . $params_encoded . '" mw_params_module="' . $params_module . '"   ' . $mod_id_tag . ' ' . $no_admin_tag . '  >' . $module_file . '</div>';

										}
									}

								} else {
									$module_file = false;
								}

								//	p($m ['full_tag']);

							} else {
								if (strval($module_file) != '') {
									$module_file = '<div ' . $more_attrs2 . ' class="module" ' . $mod_id_tag . '  mw_params_encoded="' . $params_encoded . '" mw_params_module="' . $params_module . '"  >' . $module_file . '</div>';
								}
							}
							//}  ++

							$module_file = str_replace('<mw', '<microweber', $module_file);

							if (strstr($params_module, 'source_code') == false and $error == false) {
								if (strstr($module_file, '<microweber') == true and $error == false) {
									$module_file = parse_micrwober_tags($module_file, $options);
								}
							}

							//	$layout = str_replace_count ( $m ['full_tag'],htmlentities($m ['full_tag']). $module_file, $layout, 1 );
							$layout = str_replace_count($m['full_tag'], $module_file, $layout, 1);
							$layout = replace_in_long_text($m['full_tag'], $module_file, $layout, $use_normal_replace = true);

							//$layout = str_replace ( $m ['full_tag'], $module_file, $layout );
							//$layout = str_replace_count ( '</microweber>', '', $layout, 1 );
							//	$layout = str_replace_count ( '</mw>', '', $layout, 1 );
							//$layout = str_replace_count ( '</microweber>', '', $layout, 1 );
							//$layout = str_replace ( '</mw>', '', $layout );
							//$layout = str_replace ( '</microweber>', '', $layout );

							//parse_memory_storage ( $check2, $layout );

						}

					}

				}

			}

		}

	}

	if (strstr($layout, '<editable') == true and $error == false) {

		$editmode = $CI-> core_model -> is_editmode();
		//p($editmode);
		require_once (LIBSPATH . "simplehtmldom/simple_html_dom.php");

		$html = str_get_html($layout);

		foreach ($html->find ( 'editable' ) as $m) {

			$attr = array();

			$attr['rel'] = $m -> rel;
			$attr['field'] = $m -> field;

			$get_global = false;
			if ($editmode == true) {

			}

			if ($attr['rel'] == 'global') {
				$attr['global'] = true;
				$get_global = true;
			} else {
				if ($attr['global']) {
					unset($attr['global']);
				}
				$get_global = false;
			}

			if ($attr['rel'] == 'page') {
				$attr['page'] = PAGE_ID;
			}

			if ($attr['rel'] == 'post') {
				$attr['post'] = POST_ID;
				if ($attr['post'] == false) {
					$attr['post'] = PAGE_ID;

					//$attr ['page'] = PAGE_ID;
				}
			}
			if ($attr['rel'] == 'category') {
				$attr['category'] = CATEGORY_ID;
			}

			if ($attr['rel'] == 'module') {
				$relate_to_module = true;
			} else {
				$relate_to_module = false;
			}

			if ($relate_to_module == false) {
				if ($attr['page']) {
					$data = get_page($attr['page']);
				} else if ($attr['post']) {
					$data = get_post($attr['post']);
					if ($data == false) {
						$data = get_page($attr['post']);
					}

					//p ( $data );
					//p ( $attr );
				} else if ($attr['category']) {
					$data = get_category($attr['category']);
				} else if ($attr['global']) {
					$get_global = true;
				}
			}

			$cf = false;
			$field_content = false;

			if ($get_global == true) {

				$field_content = $CI-> core_model -> optionsGetByKey($attr['field'], $return_full = false, $orderby = false);

			} else {
				if (strstr($attr['field'], 'custom_field_') == true) {
					$cf = str_replace('custom_field_', '', $attr['field']);
					$field_content = $data['custom_fields'][$cf];

				} else {
					$field_content = $data[$attr['field']];

				}
			}
			//p( $attr ['field']);
			if (strstr($attr['field'], 'source_code') == false) {

				if (trim($field_content) == '') {
					$field_content = $m -> innertext;

					//	$field_content = $m ['contents'];
				} else {
					//$quote_style = ENT_COMPAT [, string $charset = 'UTF-8' ]]
					$field_content = htmlspecialchars_decode($field_content);

					//$field_content = html_entity_decode ( $field_content, $quote_style = ENT_COMPAT, $charset = 'UTF-8' );
				}
				//	$field_content = html_entity_decode ( $field_content );
				$field_content = htmlspecialchars_decode($field_content);
				//	$field_content = $this->template_model->parse_micrwober_tags ( $field_content, $options );

				//												$check_divs = strstr ( '<div', $field_content );
				//												if ($check_divs == false) {
				//													$field_content = '<div>' . $field_content . '</div>';
				//												}

				//print htmlspecialchars ( $field_content );
				if ($editmode == true) {
					$attrs_to_append = false;
					foreach ($attr as $at_key => $at_value) {
						if ($at_key != 'field') {
							$attrs_to_append .= "$at_key='$at_value' ";

						}
					}
					//		p($attrs_to_append);
					//	$in = $m->innertext;

					$in = "<div id='{$attr['field']}' field='{$attr['field']}' class='edit' {$attrs_to_append}>" . $field_content . '</div>';

					$m -> outertext = $in;

					$layout = $html -> save();

					// clean up memory
					//$html->clear ();
					//unset ( $html );

					//p($attrs_to_append);
					//$layout = $this->core_model->replace_in_long_text ( $m ['full_tag'], "<div id='{$attr['field']}' class='edit' {$attrs_to_append}>" . $field_content . '</div>', $layout, $use_normal_replace = true );

					//$layout = str_replace_count ( $m ['full_tag'], "<div id='{$attr['field']}' class='edit' {$attrs_to_append}>" . $field_content . '</div>', $layout, 1 );
				} else {
					//$layout = str_replace_count ( $m ['full_tag'], $field_content, $layout, 1 );
					//	$layout = str_replace_count ( $m ['full_tag'], "<div id='{$attr['field']}' class='edit'>" . $field_content . '</div>', $layout, 1 );
					//$layout = $this->core_model->replace_in_long_text ( $m ['full_tag'], "<div id='{$attr['field']}' class='edit'>" . $field_content . '</div>', $layout, $use_normal_replace = true );
					$in = "<div id='{$attr['field']}'  field='{$attr['field']}'  class='edit'>" . $field_content . '</div>';
					$m -> outertext = $in;

					$layout = $html -> save();
				}

				//$layout = str_replace_count ( $m ['full_tag'], $field_content, $layout, 1 );
				//	$layout = str_replace_count ( $m ['full_tag'], "aaaaaaaa", $layout, 1 );
				//$layout = str_replace_count ( 'editable', "aaaaaaaa", $layout, 1 );
				//p($layout);
				//$layout = str_replace ( '<mw', '<microweber', $layout );

				//$layout = $this->core_model->replace_in_long_text ( '<mw', '<microweber', $layout, $use_normal_replace = true );

				if (strstr($layout, '<microweber') == true and $error == false) {
					$layout = parse_micrwober_tags($layout, $options);
				}
				if (strstr($layout, '<editable') == true and $error == false) {
					$layout = parse_micrwober_tags($layout, $options);
				}

			} else {
				//$layout = $html->save ();
			}

		}

	}

	 
	$site_url = site_url();
	$layout = replace_in_long_text('{SITE_URL}', $site_url, $layout, true);
	$layout = replace_in_long_text('{SITEURL}', $site_url, $layout, true);
	 
	if (defined('POST_ID') == true) {
		//$layout = str_replace ( '{POST_ID}', POST_ID, $layout );
		$layout = replace_in_long_text('{POST_ID}', POST_ID, $layout, true);

	}

	if (defined('PAGE_ID') == true) {
		//$layout = str_replace ( '{PAGE_ID}', PAGE_ID, $layout );
		$layout = replace_in_long_text('{PAGE_ID}', PAGE_ID, $layout, true);
	}

	if (defined('CATEGORY_ID') == true) {
		//$layout = str_replace ( '{CATEGORY_ID}', CATEGORY_ID, $layout );
		$layout = replace_in_long_text('{CATEGORY_ID}', CATEGORY_ID, $layout, true);

	}
	$layout = str_replace('</microweber>', '', $layout);
	//	$this->load->vars ( $this->template );
	if (stristr($layout, 'content_meta_title')) {

		if (defined('POST_ID') == true) {
			$is_content = get_post(POST_ID);
			$is_content_post = $is_content;
			$layout = str_replace('{POST_ID}', POST_ID, $layout);
		}

		if ($is_content == false) {
			if (defined('PAGE_ID') == true) {
				$is_content = get_page(PAGE_ID);
				$is_content_page = $is_content;
			}
		}

		if ($is_content['content_meta_title']) {
			$content_meta_title = $is_content['content_meta_title'];
		} elseif ($is_content['content_title']) {
			$content_meta_title = codeClean($is_content['content_title']);
		} else {
			$content_meta_title = $CI-> core_model -> optionsGetByKey('content_meta_title');
		}
		$layout = str_replace('{content_meta_title}', $content_meta_title, $layout);

		if ($is_content['content_meta_keywords']) {
			$content_meta_title = $is_content['content_meta_keywords'];
		} else {
			$content_meta_title = $CI-> core_model -> optionsGetByKey('content_meta_keywords');
		}
		$layout = str_replace('{content_meta_keywords}', $content_meta_title, $layout);

		if ($is_content['content_meta_description']) {
			$content_meta_title = $is_content['content_meta_description'];
		} elseif ($is_content['content_description']) {
			$content_meta_title = codeClean($is_content['content_description']);
		} elseif ($is_content['content_body']) {
			$content_meta_title = codeClean($is_content['content_body']);
		} else {
			$content_meta_title = $CI-> core_model -> optionsGetByKey('content_meta_title');
		}
		$layout = str_replace('{content_description}', $content_meta_title, $layout);

	}

	if (is_file(ACTIVE_TEMPLATE_DIR . 'controllers/pre_layout_display.php')) {

		include ACTIVE_TEMPLATE_DIR . 'controllers/pre_layout_display.php';

	}

	if ((strstr($layout, '<editable') == true) or (strstr($layout, '<mw') == true) or (strstr($layout, '<microweber') == true) or (strstr($layout, '<block') == true) and $error == false) {
		//	$layout = $this->template_model->parse_micrwober_tags ( $layout, $options );
	}

	if ((strstr($layout, '<editable') == true)) {
		//$layout = $this->template_model->parse_micrwober_tags ( $layout, $options );
	}

	if (!empty($txt_to_replace_back)) {

		foreach ($txt_to_replace_back as $k => $v) {
			//	$v = html_entity_decode($v);
			$layout = replace_in_long_text($k, $v, $layout, true);

		}
		$layout = replace_in_long_text('<nomw>', '', $layout, true);
		$layout = replace_in_long_text('</nomw>', '', $layout, true);
	}

	if (empty($relations)) {
		$layout = str_replace('</head>', $prepend_to_head . '</head>', $layout);
		//$layout= '<div class="mw_module">'.$layout.'</div>';
		$mem[$check] = $layout;
		//if ($do_not_cache_whole_block == false) {
		//$this->core_model->cacheWriteAndEncode ( $layout, $cache_id, $cache_group );
		//}
		//$this->core_model->cacheWrite ( $layout, $function_cache_id, $cache_group );
		return $layout;
	} else {
		//if ($do_not_cache_whole_block == false) {
		//$this->core_model->cacheWriteAndEncode ( $v, $cache_id, $cache_group );
		//}
		//$this->core_model->cacheWrite ( $v, $function_cache_id, $cache_group );
		$mem[$check] = $v;
		return $v;
	}

	//p($relations);
}





/**
	 *
	 * @author Peter Ivanov
	 *        
	 *         function groupsSave($data) {
	 *         $table = $table = TABLE_PREFIX . 'groups';
	 *         $criteria = $this->input->xss_clean ( $data );
	 *         $criteria = $this->core_model->mapArrayToDatabaseTable ( $table,
	 *         $data );
	 *         $save = $this->core_model->saveData ( $table, $criteria );
	 *         return $save;
	 *         }
	 */
	
	function replace_in_long_text($sRegExpPattern, $sRegExpReplacement, $sVeryLongText, $normal_replace = false) {
		$function_cache_id = false;
		
		$test_for_long = strlen ( $sVeryLongText );
		if ($test_for_long > 1000) {
			
			$args = func_get_args ();
			$i = 0;
			foreach ( $args as $k => $v ) {
				if ($i != 2) {
					$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
				} else {
				
				}
				$i ++;
			}
			
			$function_cache_id = __FUNCTION__ . md5 ( $sVeryLongText ) . md5 ( $function_cache_id );
			
			$cache_group = 'extract_tags';
			
			//$cache_content = $this->cacheGetContent ( $function_cache_id, $cache_group );
			
			if (($cache_content) != false) {
				
			//	return $cache_content;
			
			}
		}
		
		if ($normal_replace == false) {
			$iSet = 0; // Count how many times we increase the limit
			while ( $iSet < 10 ) { // If the default limit is 100'000 characters
			                       // the highest new limit will be 250'000
			                       // characters
				$sNewText = preg_replace ( $sRegExpPattern, $sRegExpReplacement, $sVeryLongText ); // Try
				                                                                                   // to
				                                                                                   // use
				                                                                                   // PREG
				
				if (preg_last_error () == PREG_BACKTRACK_LIMIT_ERROR) { // Only
				                                                        // check on
				                                                        // backtrack
				                                                        // limit
				                                                        // failure
					ini_set ( 'pcre.backtrack_limit', ( int ) ini_get ( 'pcre.backtrack_limit' ) + 15000 ); // Get
					                                                                                        // current
					                                                                                        // limit
					                                                                                        // and
					                                                                                        // increase
					$iSet ++; // Do not overkill the server
				} else { // No fail
					$sVeryLongText = $sNewText; // On failure $sNewText would be NULL
					break; // Exit loop
				}
			}
		
		} else {
			$sNewText = str_replace ( $sRegExpPattern, $sRegExpReplacement, $sVeryLongText );
			
			// $sNewText = preg_replace($sRegExpPattern,$sRegExpReplacement,
			// $sVeryLongText);
		
		}
		 
		return $sNewText;
	
	}
function parse_memory_storage($id = false, $content = false) {

	static $parse_mem = array();
	$path_md = ($id);
	// p($parse_mem);

	if ($parse_mem[$path_md] != false) {

		return $parse_mem[$path_md];
	}

	if ($content != false) {

		$parse_mem[$path_md] = $content;
		return $content;
	}
}