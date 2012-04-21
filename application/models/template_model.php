<?php

function parse_memory_storage($id, $content) {
	
	static $parse_mem;
	$path_md = ($id);
	// p($parse_mem);
	

	if ($parse_mem [$path_md] != false) {
		
		return $parse_mem [$path_md];
	}
	
	if ($content != false) {
		
		$parse_mem [$path_md] = $content;
		return $content;
	}
}

class Template_model extends CI_Model {
	public static $parse_memory = array ();
	function __construct() {
		parent::__construct();
	
		//	$this->content_model->define_vars ();
	

	}
	
	function addTransparentBackgroudToFlash($layout) {
		if (strstr ( $layout, '<object' ) == true) {
			
			$relations = array ();
			$tags = $this->core_model->extractTags ( $layout, 'object', $selfclosing = false, $return_the_entire_tag = true, $charset = 'UTF-8' );
			// p($tags);
			$matches = $tags;
			if (! empty ( $matches )) {
				//
				foreach ( $matches as $m ) {
					$full = $m ['full_tag'];
					
					if (strstr ( $full, 'wmode' ) == false) {
						
						$tag = '<param name="wmode" value="transparent"></object>';
						
						$full = str_ireplace ( '</object>', $tag, $full );
						
						$layout = str_replace_count ( $m ['full_tag'], $full, $layout, 1 );
					}
				
				}
			}
		
		}
		
		if (strstr ( $layout, '<embed' ) == true) {
			
			$relations = array ();
			$tags = $this->core_model->extractTags ( $layout, 'embed', $selfclosing = true, $return_the_entire_tag = true, $charset = 'UTF-8' );
			
			$matches = $tags;
			if (! empty ( $matches )) {
				//
				foreach ( $matches as $m ) {
					$full = $m ['full_tag'];
					
					if (strstr ( $full, 'wmode' ) == false) {
						
						$tag = '<embed wmode="transparent" ';
						
						$full = str_ireplace ( '<embed', $tag, $full );
						
						$layout = str_replace_count ( $m ['full_tag'], $full, $layout, 1 );
					}
				
				}
			}
			
			$tags = $this->core_model->extractTags ( $layout, 'embed', $selfclosing = false, $return_the_entire_tag = true, $charset = 'UTF-8' );
			
			$matches = $tags;
			if (! empty ( $matches )) {
				//
				foreach ( $matches as $m ) {
					$full = $m ['full_tag'];
					
					if (strstr ( $full, 'wmode' ) == false) {
						
						$tag = '<embed wmode="transparent" ';
						
						$full = str_ireplace ( '<embed', $tag, $full );
						
						$layout = str_replace_count ( $m ['full_tag'], $full, $layout, 1 );
					}
				
				}
			}
		
		}
		
		return $layout;
	}
	
	function replaceTemplateTags($layout) {
		// moved to content model
		// @todo cleaup here
		

		$html = $this->content_model->applyGlobalTemplateReplaceables ( $layout );
		
		/*require_once 'htmlsql-v0.5/htmlsql.class.php';
		 require_once ("htmlsql-v0.5/snoopy.class.php");

		 $wsql = new htmlsql ( );
		 $html = $layout;
		 // connect to a string
		 if (! $wsql->connect ( 'string', $html )) {
		 return $layout;
		 }

		 //if (! $wsql->query ( 'SELECT * FROM to_table  ' )) {
		 //if (! $wsql->query ( 'SELECT * FROM microweber ' )) {
		 if (! $wsql->query ( 'SELECT * FROM * WHERE $class == "remove-on-submit" ' )) {

		 //return $layout;
		 }

		 $arr = $wsql->fetch_array ();
		 if (! empty ( $arr )) {
		 foreach ( $arr as $row ) {
		 $json = $row ['text'];
		 if (trim ( $json ) != '') {
		 //$result = json_decode ( $json, 1 );

		 p ( $result );

		 }

		 }
		 }
		 */
		return $html;
	}
	
	function layoutGet($filename) {
		$the_active_site_template = $this->core_model->optionsGetByKey ( 'curent_template' );
		$path = TEMPLATEFILES . '' . $the_active_site_template . '/layouts/';
		$layout_path = $path;
		//$file = @file_get_contents ( $layout_path. $filename );
		$file = $this->load->file ( $layout_path . $filename, true );
		return $file;
	}
	
	function layoutGetConfig($filename, $template = false) {
		if (trim ( $template ) == '' or strtolower ( $template ) == 'default') {
			$the_active_site_template = $this->core_model->optionsGetByKey ( 'curent_template' );
		} else {
			
			$the_active_site_template = $template;
		}
		$path = TEMPLATEFILES . '' . $the_active_site_template . '/layouts/';
		$layout_path = $path;
		//$file = @file_get_contents ( $layout_path. $filename );
		

		$try = $layout_path . $filename;
		$try1 = str_ireplace ( '.php', '.png', $try );
		$screensshot_file = $try1;
		$screensshot_file = normalize_path ( $screensshot_file, false );
		//p($screensshot_file);
		

		$try = str_ireplace ( '.php', '_config.php', $try );
		if (is_file ( $try )) {
			include ($try);
		
		//$file = $this->load->file ( $try, true );
		} else {
			$try = $layout_path . $filename;
			$try = dirname ( $try );
			$try = $try . DIRECTORY_SEPARATOR . 'config.php';
			if (is_file ( $try )) {
				include ($try);
			
		//$file = $this->load->file ( $try, true );
			}
		}
		//p ( $try );
		//	p($screensshot_file);
		if (is_file ( $screensshot_file )) {
			$config ['screenshot'] = pathToURL ( $screensshot_file );
		}
		
		return $config;
	}
	
	/**
	 * @desc  Get the template layouts info under the layouts subdir on your active template
	 * @param $options
	 * $options ['type'] - 'layout' is the default type if you dont define any. You can define your own types as post/form, etc in the layout.txt file
	 * @return array
	 * @author	Microweber Dev Team
	 * @since Version 1.0
	 */
	function templatesList($options = false) {
		CI::helper ( 'directory' );
		//$path = BASEPATH . 'content/templates/';
		

		$path = TEMPLATEFILES;
		$path_to_layouts = $path;
		$layout_path = $path;
		//	print $path;
		//exit;
		

		//$map = directory_map ( $path, TRUE );
		$map = directory_map ( $path, TRUE, TRUE );
		//var_dump ( $map );
		$to_return = array ();
		
		foreach ( $map as $dir ) {
			
			//$filename = $path . $dir . DIRECTORY_SEPARATOR . 'layout.php';
			$filename = $path . DIRECTORY_SEPARATOR . $dir;
			$filename_location = false;
			$filename_dir = false;
			$filename = normalize_path ( $filename );
			$filename = rtrim ( $filename, '\\' );
			//p ( $filename );
			if (is_dir ( $filename )) {
				//
				$fn1 = normalize_path ( $filename, true ) . 'config.php';
				$fn2 = normalize_path ( $filename );
				
				//  p ( $fn1 );
				

				if (is_file ( $fn1 )) {
					$config = false;
					
					include ($fn1);
					if (! empty ( $config )) {
						$c = $config;
						$c ['dir_name'] = $dir;
						
						$screensshot_file = $fn2 . '/screenshot.png';
						$screensshot_file = normalize_path ( $screensshot_file, false );
						//p($screensshot_file);
						if (is_file ( $screensshot_file )) {
							$c ['screenshot'] = pathToURL ( $screensshot_file );
						}
						
						$to_return [] = $c;
					}
				
				} else {
					$filename_dir = false;
				}
			
		//	$path = $filename;
			}
		
		//p($filename);
		

		}
		return $to_return;
	}
	
	/**
	 * @desc  Get the template layouts info under the layouts subdir on your active template
	 * @param $options
	 * $options ['type'] - 'layout' is the default type if you dont define any. You can define your own types as post/form, etc in the layout.txt file
	 * @return array
	 * @author	Microweber Dev Team
	 * @since Version 1.0
	 */
	function layoutsList($options = false) {
		CI::helper ( 'directory' );
		//$path = BASEPATH . 'content/templates/';
		

		if ($options ['site_template'] and (strtolower ( $options ['site_template'] ) != 'default')) {
			$tmpl = trim ( $options ['site_template'] );
			$check_dir = TEMPLATEFILES . '' . $tmpl . '/layouts/';
			if (is_dir ( $check_dir )) {
				$the_active_site_template = $tmpl;
			} else {
				$the_active_site_template = $this->core_model->optionsGetByKey ( 'curent_template' );
			
			}
		
		} else {
			$the_active_site_template = $this->core_model->optionsGetByKey ( 'curent_template' );
		
		}
		
		$path = TEMPLATEFILES . '' . $the_active_site_template . '/layouts/';
		$path_to_layouts = $path;
		$layout_path = $path;
		//	print $path;
		//exit;
		

		//$map = directory_map ( $path, TRUE );
		$map = directory_map ( $path, TRUE, TRUE );
		//var_dump($map);
		$to_return = array ();
		
		foreach ( $map as $dir ) {
			
			//$filename = $path . $dir . DIRECTORY_SEPARATOR . 'layout.php';
			$filename = $path . $dir;
			$filename_location = false;
			$filename_dir = false;
			$filename = normalize_path ( $filename );
			$filename = rtrim ( $filename, '\\' );
			//p ( $filename );
			if (is_dir ( $filename )) {
				//
				$fn1 = normalize_path ( $filename ) . 'index.php';
				$fn2 = normalize_path ( $filename );
				
				//  p ( $fn1 );
				

				$default_config_location = false;
				$default_config_location_full_path = false;
				if (is_file ( $fn1 )) {
					$filename = $fn1;
					$filename_dir = $dir;
					$filename_location = $dir . '/index.php';
					
					$default_config_location = normalize_path ( $fn2 );
					$default_config_location2 = $default_config_location;
					//$default_config_location = rtrim ( $default_config_location, '\\' );
					

					$default_config_location_full_path = $default_config_location . 'config.php';
					if (is_file ( $default_config_location_full_path )) {
						$default_config_location = $dir . '/config.php';
					}
					
					$default_custom_fields = $default_config_location2 . 'custom_fields.php';
					//p($default_custom_fields);
					if (is_file ( $default_custom_fields )) {
						$default_custom_fields = $default_custom_fields;
					} else {
						$default_custom_fields = false;
					}
				
				} else {
					$filename_dir = false;
				}
			
		//	$path = $filename;
			}
			//p($filename);
			if (is_file ( $filename )) {
				
				$ext = file_extension ( $filename );
				if ($ext == 'php') {
					
					$filename_no_ext = $file = basename ( $filename, "." . $ext );
					//	$txt_file = $path . $dir . DIRECTORY_SEPARATOR . $filename_no_ext . '.txt';
					//	$the_file = str_replace($path_to_layouts, '',$filename );;
					

					//if (is_file ( $txt_file )) {
					$fin = cache_file_memory_storage ( $filename );
					if (preg_match ( '/type:.+/', $fin, $regs )) {
						$result = $regs [0];
						$result = str_ireplace ( 'type:', '', $result );
						$to_return_temp ['type'] = trim ( $result );
					}
					
					if ($options ['type'] == '') {
						$options ['type'] = 'layout';
					}
					if (strtolower ( $to_return_temp ['type'] ) == strtolower ( $options ['type'] )) {
						$to_return_temp = array ();
						
						//$to_return_temp ['dir'] = trim ( $dir );
						if (preg_match ( '/description:.+/', $fin, $regs )) {
							$result = $regs [0];
							$result = str_ireplace ( 'description:', '', $result );
							$to_return_temp ['description'] = trim ( $result );
						}
						if (preg_match ( '/name:.+/', $fin, $regs )) {
							$result = $regs [0];
							$result = str_ireplace ( 'name:', '', $result );
							$to_return_temp ['name'] = trim ( $result );
						}
						
						if (preg_match ( '/content_type:.+/', $fin, $regs )) {
							$result = $regs [0];
							$result = str_ireplace ( 'content_type:', '', $result );
							$to_return_temp ['content_type'] = trim ( $result );
						}
						$screensshot_file = $path . $filename_dir . DIRECTORY_SEPARATOR . $filename_no_ext . '.png';
						$screensshot_file = normalize_path ( $screensshot_file, false );
						//p($screensshot_file);
						if (is_file ( $screensshot_file )) {
							$to_return_temp ['screenshot'] = pathToURL ( $screensshot_file );
						}
						if ($filename_dir != false) {
							$to_return_temp ['layout_name'] = $filename_dir;
						}
						if ($filename_location == false) {
							
							$to_return_temp ['filename'] = $dir;
						} else {
							$to_return_temp ['filename'] = $filename_location;
							
							if ($default_config_location_full_path != false) {
								if (is_file ( $default_config_location_full_path )) {
									include ($default_config_location_full_path);
									$to_return_temp ['config'] = ($config);
									$to_return_temp ['params'] = ($config ['params']);
								
								}
								
								if ($default_custom_fields != false) {
									
									include ($default_custom_fields);
									$to_return_temp ['custom_fields'] = ($custom_fields);
								
		//p($custom_fields);
								}
							}
						
						}
						
						//$screens_dir = $path . $dir . DIRECTORY_SEPARATOR . 'screenshots' . DIRECTORY_SEPARATOR;
						//p($screens_dir);
						/*if (is_dir ( $screens_dir )) {
						 $screens_dir = array_filter ( glob ( $screens_dir . '*.jpg' ), 'is_file' );
						 //$screens_dir = readDirIntoArray($screens_dir, 'files');
						 //var_dump($screens_dir);
						 if (! empty ( $screens_dir )) {
						 $screenshots = array ();
						 foreach ( $screens_dir as $screens_file ) {
						 $screens_file = pathToURL ( $screens_file );
						 $screenshots [] = ($screens_file);
						 }
						 $to_return_temp ['screenshots'] = $screenshots;
						 }
						 }*/
						//p ( $to_return_temp );
						$to_return [] = $to_return_temp;
					}
				
		//}
				}
			}
		
		}
		return $to_return;
	}
	
	/**
	 * @desc  Get the template layouts html by dir name
	 * @param string dir name under the layouts subdir on your active template
	 * @return string
	 * @author	Microweber Dev Team
	 * @version 1.0
	 * @since Version 1.0
	 */
	function layoutGetHTMLByDirName($layout_name) {
		CI::helper ( 'directory' );
		//$path = BASEPATH . 'content/templates/';
		$the_active_site_template = $this->core_model->optionsGetByKey ( 'curent_template' );
		$path = TEMPLATEFILES . '' . $the_active_site_template . '/layouts/';
		//	print $path;
		//exit;
		

		$filename = $path . $layout_name . DIRECTORY_SEPARATOR . 'layout.php';
		if (is_file ( $filename )) {
			$html = cache_file_memory_storage ( $filename );
			if ($html != '') {
				require_once 'htmlsql-v0.5/htmlsql.class.php';
				require_once ("htmlsql-v0.5/snoopy.class.php");
				
				$wsql = new htmlsql ();
				
				// connect to a string
				if (! $wsql->connect ( 'string', $html )) {
					print 'Error while connecting: ' . $wsql->error;
					exit ();
				}
				
				if (! $wsql->query ( 'SELECT * FROM img' )) {
					print "Query error: " . $wsql->error;
					exit ();
				}
				$path_styles = TEMPLATEFILES . '' . $the_active_site_template . '/layouts/' . $layout_name . '/styles/';
				if (is_dir ( $path_styles ) == false) {
					$path_styles = TEMPLATEFILES . '' . $the_active_site_template . '/layouts/' . $layout_name . '/';
				}
				
				if (is_dir ( $path_styles ) == false) {
					$path_styles = TEMPLATEFILES . '' . $the_active_site_template . '/layouts/';
				}
				// fetch results as array and output them:
				$arr = $wsql->fetch_array ();
				if (! empty ( $arr )) {
					foreach ( $arr as $row ) {
						if ((stristr ( $row ['src'], 'http://' ) == false) or (stristr ( $row ['src'], 'https://' ) == false) or (stristr ( $row ['src'], 'ftp://' ) == false)) {
							$url = pathToURL ( $path_styles . $row ['src'] );
							$html = str_ireplace ( $row ['src'], $url, $html );
						}
					}
				}
			}
		}
		return $html;
	}
	
	/**
	 * @desc  Get the template layouts styles fom the $layout_name/styles dir
	 * @param string dir name under the layouts subdir on your active template
	 * @return string
	 * @author	Microweber Dev Team
	 * @version 1.0
	 * @since Version 1.0
	 */
	function stylesList($layout_name) {
		
		CI::helper ( 'directory' );
		//$path = BASEPATH . 'content/templates/';
		$the_active_site_template = $this->core_model->optionsGetByKey ( 'curent_template' );
		$path = TEMPLATEFILES . '' . $the_active_site_template . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $layout_name . DIRECTORY_SEPARATOR . 'styles' . DIRECTORY_SEPARATOR;
		//int $path;
		//exit;
		

		//	$map = directory_map ( $path, TRUE );
		//r_dump($map);
		$map = directory_map ( $path, TRUE, TRUE );
		//var_dump($map);
		$to_return = array ();
		
		foreach ( $map as $file ) {
			$filename = $path . $file;
			//var_dump($filename);
			if (is_file ( $filename )) {
				$ext = file_extension ( $filename );
				$filename_no_ext = basename ( $filename, "." . $ext );
				$txt_file = $path . $dir . DIRECTORY_SEPARATOR . $filename_no_ext . '.txt';
				$img_file = $path . $dir . DIRECTORY_SEPARATOR . $filename_no_ext . '.jpg';
				$ext = end ( explode ( ".", $filename ) );
				//var_dump($filename_no_ext);
				if ($ext == 'css') {
					$filename2 = $txt_file;
					if (is_file ( $filename2 )) {
						$fin = cache_file_memory_storage ( $filename2 );
						$to_return_temp = array ();
						$to_return_temp ['filename'] = $file;
						
						if (is_file ( $img_file )) {
							$screens_file = pathToURL ( $img_file );
							$to_return_temp ['screenshot'] = trim ( $screens_file );
						}
						
						if (preg_match ( '/description:.+/', $fin, $regs )) {
							$result = $regs [0];
							$result = str_ireplace ( 'description:', '', $result );
							$to_return_temp ['description'] = trim ( $result );
						}
						if (preg_match ( '/name:.+/', $fin, $regs )) {
							$result = $regs [0];
							$result = str_ireplace ( 'name:', '', $result );
							$to_return_temp ['name'] = trim ( $result );
						}
						
						if (preg_match ( '/type:.+/', $fin, $regs )) {
							$result = $regs [0];
							$result = str_ireplace ( 'type:', '', $result );
							$to_return_temp ['type'] = trim ( $result );
						}
						
						//if ($to_return_temp ['type'] == 'layout') {
						if ($to_return_temp ['name'] != false) {
							$to_return [] = $to_return_temp;
						}
					
		//}
					}
				}
			
			}
		
		}
		return $to_return;
	
	}
	
	/**
	 * @desc  Get the laoyts style CSS's in TinyMCE format http://wiki.moxiecode.com/index.php/TinyMCE:Configuration/content_css
	 * @param string $layout_name
	 * @param string $style_css
	 * @return string
	 * @author	Microweber Dev Team
	 * @version 1.0
	 * @since Version 1.0
	 */
	function styleGetCSSURLsAsString($layout_name, $style_css = false) {
		$the_active_site_template = $this->core_model->optionsGetByKey ( 'curent_template' );
		$path_layout_css = TEMPLATEFILES . '' . $the_active_site_template . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $layout_name . DIRECTORY_SEPARATOR . 'layout.css';
		$path_style_css = TEMPLATEFILES . '' . $the_active_site_template . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $layout_name . DIRECTORY_SEPARATOR . 'styles' . DIRECTORY_SEPARATOR . $style_css;
		$defalt_style_css = TEMPLATEFILES . '' . $the_active_site_template . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $layout_name . DIRECTORY_SEPARATOR . 'styles' . DIRECTORY_SEPARATOR . 'default.css';
		$styles = array ();
		if (is_file ( $path_layout_css )) {
			$styles [] = pathToURL ( $path_layout_css );
		}
		if (is_file ( $path_style_css )) {
			$styles [] = pathToURL ( $path_style_css );
		} else {
			if (is_file ( $defalt_style_css )) {
				$styles [] = pathToURL ( $defalt_style_css );
			}
		}
		$styles = implode ( ',', $styles );
		return $styles;
	}
	
	/**
	 * @desc  Generate Microweber tags to use in tinymce editor
	 * @param $data
	 * @param $options
	 * $options ['no_microwber_tags'] - default: false - removes the <microweber> tags
	 * $options ['no_remove_div'] - default: false - removes the <div class="remove-on-submit"> tags
	 * $options ['get_only_stylesheets_as_csv'] - default: false - return the CSS files urls as csv string
	 * @return string
	 * @author	Microweber Dev Team
	 * @version 1.0
	 * @since Version 1.0
	 */
	function microweberTagsGenerate($data, $options = false) {
		//var_dump ( $data );
		if (empty ( $data )) {
			return false;
		}
		
		if ($data ['to_table'] == false) {
			return false;
		}
		
		if ($data ['to_table'] == 'table_users') {
			return false;
			exit ( 'Sorry you cant access the users table due privacy protection for your website.' );
		}
		
		if ($data ['type'] == false) {
			return false;
		}
		
		if ($data ['to_table_id'] == false) {
			return false;
		}
		//"type":"content","to_table":"table_content","to_table_id":"'+$content_id+'","to_table_field":"content_body"
		

		if ($data ['to_table_field'] != '') {
			//$res = CI::model('core')->fetchDbData ( $data ['to_table'], array (array ('is_active', 'y' ), array ('id', $data ['to_table_id'] ) ), array ('debug' => false, 'cache_group' => false, 'order' => array (array ('id', 'DESC' ) ) ) );
		

		}
		$res = $this->parseDynamicRelations ( $data );
		
		if ($options ['get_only_stylesheets_as_csv'] == false) {
			$mwtag = json_encode ( $data );
			if ($options ['no_microwber_tags'] == false) {
				$mwtag = '<microweber>' . $mwtag . '</microweber>';
			} else {
				$mwtag = false;
			}
			
			if ($options ['no_remove_div'] == false) {
				$field = '<div class="remove-on-submit">' . $res ["{$data ['to_table_field']}"] . '</div>';
			} else {
				$field = $res ["{$data ['to_table_field']}"];
			}
			//$prepend_to_head = '<link href="http://img.abv.bg/dHome/dragcss/styles_09.css" type="text/css" />';
			return ($prepend_to_head . $mwtag . $field);
		} else {
			$relation = $res;
			
			$file1 = LAYOUTS_DIR . $relation ['content_layout_name'] . '/layout.css';
			$file1_url = LAYOUTS_URL . $relation ['content_layout_name'] . '/layout.css';
			
			$file2 = LAYOUTS_DIR . $relation ['content_layout_name'] . '/styles/' . $parsed ['content_layout_style'];
			$file2_url = LAYOUTS_URL . $relation ['content_layout_name'] . '/styles/' . $parsed ['content_layout_style'];
			
			$file3 = LAYOUTS_DIR . $relation ['content_layout_name'] . '/styles/default.css';
			$file3_url = LAYOUTS_URL . $relation ['content_layout_name'] . '/styles/default.css';
			
			//print $file1;
			$return = array ();
			if (is_file ( $file1 ) == true) {
				$return [] = $file1_url;
			}
			if (is_file ( $file2 ) == true) {
				$return [] = $file2_url;
			} else {
				if (is_file ( $file3 ) == true) {
					$return [] = $file3_url;
				}
			}
			//	p($return);
			if (! empty ( $return )) {
				return implode ( ',', $return );
			} else {
				return false;
			}
		
		//p ( $options );
		}
	}
	
	function parseToTags($content) {
		
		//require_once (LIBSPATH . "simplehtmldom/simple_html_dom.php");
		

		$html = str_get_html ( $content );
		foreach ( $html->find ( 'div[field]' ) as $checkbox ) {
			//var_Dump($checkbox);
			$re1 = $checkbox->rel;
			$style = $checkbox->field;
			
			if ($checkbox->page) {
				$checkbox->page = "{PAGE_ID}";
			}
			
			if ($checkbox->post) {
				$checkbox->post = "{POST_ID}";
			}
			
			$re2 = $checkbox->mw_params_module;
			
			$inner = $checkbox->innertext;
			
			$tag1 = "<editable ";
			$tag1 = $tag1 . "rel=\"{$re1}\" ";
			
			$tag1 = $tag1 . "field=\"{$style}\" ";
			$tag1 .= ">" . $inner . "</editable>";
			//p($tag1);
			$checkbox->outertext = $tag1;
			$html->save ();
		
		}
		
		$content = $html->save ();
		
		return $content;
		
		p ( $content, 1 );
		$relations = array ();
		$tags = $this->core_model->extractTags ( $content, 'div', $selfclosing = false, $return_the_entire_tag = true, $charset = 'UTF-8' );
		//
		$matches = $tags;
		if (! empty ( $matches )) {
			//
			

			foreach ( $matches as $m ) {
				
				$cl = $m ["attributes"] [0];
				$cl2 = $m ["attributes"] ["type"];
				$cl3 = $m ["attributes"] ["data-type"];
				p ( $cl2 );
				p ( $m ["attributes"] );
				//	if (strstr ( $cl, 'edit' ) != false) {
				if (strstr ( $cl2, 'editable' ) != false) {
					//if (strstr ( $cl2, 'editable' ) != false) {
					$QueryString = '';
					foreach ( $m ["attributes"] as $Key => $Value ) {
						$Value = str_replace ( 'mercury-region', '', $Value );
						$Value = str_replace ( '-handle', '', $Value );
						$Value = str_replace ( '-e', '', $Value );
						
						$Value = trim ( $Value );
						$Key = trim ( $Key );
						if ($Key != 'page' and ($Key != 'post')) {
							
							$QueryString .= " " . $Key . '="' . $Value . '"';
						}
					}
					
					$rep = "<editable " . $QueryString . "></editable>";
					
					//$replaced = false;
					$attr = $m ['attributes'];
					$tag = $m ['full_tag'];
					
					$content = str_replace ( $tag, $rep, $content );
					
					if (strstr ( $content, 'div data-type="editable"' ) == true) {
						
						$content = $this->parseToTags ( $content );
					
					}
				
		//	p ( $tag );
				}
			
			}
		
		}
		// exit ($content);
		return $content;
	}
	
	/**
	 * @desc  Parses the relations array and returns the aprropriate data. Define your custom parser with the 'type' option
	 * @param $data
	 * @param $options
	 * $options ['to_table'] - give the table name
	 * $options ['to_table_id'] - give the id
	 * $options ['type'] 	- if 'content' - get data from the database
	 * - no other types defined for now
	 * @return string
	 * @author	Microweber Dev Team
	 * @version 1.0
	 * @since Version 1.0
	 */
	function parseDynamicRelations($options) {
		$data = $options;
		if (empty ( $data )) {
			return false;
		}
		
		if ($data ['to_table'] == false) {
			return false;
		}
		
		if ($data ['to_table'] == 'table_users') {
			exit ( 'Sorry you cant access the users table due privacy protection for your website.' );
		}
		
		if ($data ['type'] == false) {
			return false;
		}
		if ($data ['type'] == 'content') {
			if (strval ( $data ['to_table'] ) != '') {
				if (strval ( $data ['to_table_id'] ) != '') {
					$res = $this->core_model->fetchDbData ( $data ['to_table'], array (array ('is_active', 'y' ), array ('id', $data ['to_table_id'] ) ), array ('debug' => false, 'cache_group' => false, 'order' => array (array ('id', 'DESC' ) ) ) );
					$res = $res [0];
				} else {
					
					print __FUNCTION__ . ' is not yet finished at line:  ' . __LINE__;
					
				/*$res = CI::model('core')->fetchDbData ( $data ['to_table'],
					 array (
					 array ('is_active', 'y' ),
					 //array ('id', $data ['to_table_id'] ) ),
					 array ('debug' => false,
					 'cache_group' => false,
					 'order' => array (array ('id', 'DESC' ) ) ) );*/
				
				}
			}
		}
		
		return $res;
	}
	
	function loadEditBlock($id, $page_id = false, $history_file = false) {
		
		if ($history_file == false) {
			
			if ($page_id == false) {
				$try_file = TEMPLATE_DIR . 'blocks/' . $id . '.php';
			} else {
				$try_file = TEMPLATE_DIR . 'blocks/' . $page_id;
				$try_file = normalize_path ( $try_file );
				$try_file .= $id . '.php';
				
				if (is_file ( $try_file ) == false) {
					$try_file = TEMPLATE_DIR . 'blocks/' . $id . '.php';
				
				}
			}
		} else {
			$try_file = $history_file;
		}
		//p($try_file);
		$module_file = $this->load->file ( $try_file, true );
		//$module_file = html_entity_decode ( $module_file );
		

		$module_file = $this->parseMicrwoberTags ( $module_file );
		return $module_file;
	}
	
	function saveEditBlock($id, $content, $page_id = false) {
		
		$content = html_entity_decode ( $content );
		
		$content = str_ireplace ( '</mw>', '', $content );
		$content = str_ireplace ( ' style=""', '', $content );
		$content = str_ireplace ( ' class="create_module"', '', $content );
		
		if ($page_id == true) {
			$the_dir = TEMPLATE_DIR . 'blocks/' . $page_id;
			$the_dir = normalize_path ( $the_dir );
			$try_file = $the_dir . $id . '.php';
			if (is_dir ( $the_dir ) == false) {
				mkdir_recursive ( $the_dir );
			}
			
			if (is_file ( $try_file )) {
			
			} else {
				
				touch ( $try_file );
			}
		} else {
			$try_file = TEMPLATE_DIR . 'blocks/' . $id . '.php';
			$the_dir = normalize_path ( TEMPLATE_DIR . 'blocks/' );
		}
		//p($try_file);
		$to_save = array ();
		
		//p($try_file);
		

		if (is_file ( $try_file )) {
			
			if (strstr ( $content, '<microweber' ) == true) {
				
				$relations = array ();
				$tags = $this->core_model->extractTags ( $content, 'microweber', $selfclosing = true, $return_the_entire_tag = true, $charset = 'UTF-8' );
				//	p($tags);
				$matches = $tags;
				if (! empty ( $matches )) {
					//
					foreach ( $matches as $m ) {
						
						//
						

						if ($m ['tag_name'] == 'microweber') {
							$replaced = false;
							$attr = $m ['attributes'];
							$tag = $m ['full_tag'];
							$to_save [] = $tag;
							if ($tag != false) {
								$content = str_ireplace ( $m ['full_tag'], '', $content );
							}
						
						}
					
					}
				
				}
			
			}
			
			if (strstr ( $content, '<div' ) == true) {
				
				$relations = array ();
				$tags = $this->core_model->extractTags ( $content, 'div', $selfclosing = false, $return_the_entire_tag = true, $charset = 'UTF-8' );
				//	p($tags);
				$matches = $tags;
				if (! empty ( $matches )) {
					//
					foreach ( $matches as $m ) {
						
						//
						

						if ($m ['tag_name'] == 'div') {
							$replaced = false;
							$attr = $m ['attributes'];
							
							if ($attr ['class'] == 'module') {
								if ($attr ['base64_array'] != '') {
									
									$base64_array = base64_decode ( $attr ['base64_array'] );
									$base64_array = unserialize ( $base64_array );
									if (! empty ( $base64_array )) {
										$tag1 = "<microweber ";
										
										foreach ( $base64_array as $k => $v ) {
											if ((strtolower ( trim ( $k ) ) != 'save') and (strtolower ( trim ( $k ) ) != 'submit')) {
												$tag1 = $tag1 . "{$k}=\"{$v}\" ";
											}
										}
										$tag1 .= " />";
										$to_save [] = $tag1;
										
										$content = str_ireplace ( $m ['full_tag'], $tag1, $content );
										$replaced = true;
									
		//p($base64_array);
									}
								}
								if ($replaced == false) {
									if ($attr ['edit'] != '') {
										$tag = ($attr ['edit']);
										//$tag = base64_decode ( $tag );
										$tag = 'edit_tag';
										
										//p ( $tag );
										

										if (strstr ( $tag, 'module_id=' ) == false) {
											
											$tag = str_replace ( '/>', ' module_id="module_' . date ( 'Ymdhis' ) . rand () . '" />', $tag );
										
										}
										
										$to_save [] = $tag;
										if ($tag != false) {
											$content = str_ireplace ( $m ['full_tag'], $tag, $content );
										}
									}
								}
							}
						
						}
					
					}
				
				}
			
			}
			
			if (! empty ( $to_save )) {
				$to_save_text = implode ( "\n", $to_save );
			}
			
			//	p ( $try_file );
			

			if ($to_save_text != '') {
				
				$to_save_text = str_replace ( '\\', '/', $to_save_text );
				print ($to_save_text) ;
				//copy for hiustory
				$today = date ( 'Y-m-d H-i-s' );
				$history_f = md5 ( $try_file );
				//$history_dir = $the_dir . '/history/' . $id . '/';
				

				$history_dir = APPPATH . '/history/blocks/' . $id . '/';
				
				$history_dir = normalize_path ( $history_dir );
				
				if (is_dir ( $history_dir ) == false) {
					mkdir_recursive ( $history_dir );
				}
				$history_file = $history_dir . $today . '.php';
				$saveh = array ();
				$saveh ['value'] = $to_save_text;
				$saveh ['full_path'] = $history_file;
				$this->core_model->saveHistory ( $saveh );
				
				//copy ( $try_file, $history_file );
				

				file_put_contents ( $try_file, $to_save_text );
				$this->core_model->cleanCacheGroup ( 'global/blocks' );
			}
		}
	
	}
	
	/**

	 * @desc Function getModules
	 * @param array
	 * @return array
	 * @author      Peter Ivanov
	 * @version 1.0
	 * @since Version 1.0
	 * @example
	 $modules_options = array();
	 $modules_options['skip_admin'] = true;
	 $modules_options['ui'] = true;

	 $modules = CI::model('template')->getModules($modules_options );

	 p($modules );

	 */
	function getDesignElementsDirs($options = false) {
		
		$dir_name = normalize_path ( ELEMENTS_DIR );
		$a1 = array ();
		$dirs = array_filter ( glob ( $dir_name . '*' ), 'is_dir' );
		foreach ( $dirs as $dir ) {
			$a1 [] = basename ( $dir );
		
		}
		
		return $a1;
	}
	
	function getDesignElements($options = false) {
		//p($options);
		if (! $options ['dir_name']) {
			$dir_name = normalize_path ( ELEMENTS_DIR );
		} else {
			$dir_name = normalize_path ( ELEMENTS_DIR . '/' . $options ['dir_name'] );
		}
		$dir = rglob ( '*_config.php', 0, $dir_name );
		
		if (! empty ( $dir )) {
			$configs = array ();
			foreach ( $dir as $key => $value ) {
				$skip_module = false;
				if ($options ['skip_admin'] == true) {
					//p($value);
					if (strstr ( $value, 'admin' )) {
						$skip_module = true;
					}
				}
				
				if ($skip_module == false) {
					
					$config = array ();
					$value = normalize_path ( $value, false );
					$value_fn = $mod_name = str_replace ( '_config.php', '', $value );
					$value_fn = str_replace ( $dir_name, '', $value_fn );
					
					$value_fn = reduce_double_slashes ( $value_fn );
					//p($value);
					$try_icon = $mod_name . '.png';
					$def_icon = MODULES_DIR . 'default.png';
					include ($value);
					$config ['module'] = $value_fn . '';
					
					if (is_file ( $try_icon )) {
						//p($try_icon);
						$config ['icon'] = pathToURL ( $try_icon );
					} else {
						$config ['icon'] = pathToURL ( $def_icon );
					
					}
					
					$mmd5 = md5 ( $config ['module'] );
					$check_if_uninstalled = ELEMENTS_DIR . '_system/' . $mmd5 . '.php';
					if (is_file ( $check_if_uninstalled )) {
						$config ['uninstalled'] = true;
						$config ['installed'] = false;
					} else {
						$config ['uninstalled'] = false;
						$config ['installed'] = true;
						$config ['file'] = $value_fn;
					
		//$config ['content'] = file_get_contents();
					

					}
					
					if ($options ['ui'] == true) {
						if ($config ['ui'] == false) {
							//	$skip_module = true;
						}
					}
					
					if ($skip_module == false) {
						$configs [] = $config;
					}
				}
			
		//p ( $value );
			}
			
			return $configs;
		}
	
	}
	
	function getModuleConfig($module_name) {
		
		$config = false;
		$params ['module_info'] = $module_name;
		if ($params ['module_info']) {
			$params ['module_info'] = str_replace ( '..', '', $params ['module_info'] );
			$try_config_file = MODULES_DIR . '' . $params ['module_info'] . '_config.php';
			if (is_file ( $try_config_file )) {
				include ($try_config_file);
				if ($config ['icon'] == false) {
					$config ['icon'] = MODULES_DIR . '' . $params ['module_info'] . '.png';
					
					$config ['icon'] = pathToURL ( $config ['icon'] );
				}
			
			}
			return $config;
		}
	
	}
	
	function getModules($options = false) {
		//p($options);
		$dir_name = normalize_path ( MODULES_DIR );
		$dir = rglob ( '*_config.php', 0, $dir_name );
		
		if (! empty ( $dir )) {
			$configs = array ();
			foreach ( $dir as $key => $value ) {
				$skip_module = false;
				if ($options ['skip_admin'] == true) {
					//p($value);
					if (strstr ( $value, 'admin' )) {
						$skip_module = true;
					}
				}
				
				if ($skip_module == false) {
					
					$config = array ();
					$value = normalize_path ( $value, false );
					$value_fn = $mod_name = str_replace ( '_config.php', '', $value );
					$value_fn = str_replace ( $dir_name, '', $value_fn );
					
					$value_fn = reduce_double_slashes ( $value_fn );
					//p($value);
					$try_icon = $mod_name . '.png';
					$def_icon = MODULES_DIR . 'default.png';
					include ($value);
					$config ['module'] = $value_fn . '';
					
					if (is_file ( $try_icon )) {
						//p($try_icon);
						$config ['icon'] = pathToURL ( $try_icon );
					} else {
						$config ['icon'] = pathToURL ( $def_icon );
					
					}
					
					$mmd5 = md5 ( $config ['module'] );
					$check_if_uninstalled = MODULES_DIR . '_system/' . $mmd5 . '.php';
					if (is_file ( $check_if_uninstalled )) {
						$config ['uninstalled'] = true;
						$config ['installed'] = false;
					} else {
						$config ['uninstalled'] = false;
						$config ['installed'] = true;
					}
					
					if ($options ['ui'] == true) {
						if ($config ['ui'] == false) {
							$skip_module = true;
						}
					}
					
					if ($skip_module == false) {
						$configs [] = $config;
					}
				}
			
		//p ( $value );
			}
			
			return $configs;
		}
	
	}
	
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
	function parseMicrwoberTags($layout, $options = false) {
		
		if (! defined ( 'PAGE_ID' )) {
			
			if (intval ( PAGE_ID ) == 0) {
				
				$p = url ( $skip_ajax = false );
				$page = $this->content_model->getPageByURLAndCache ( $p );
				
				define ( "PAGE_ID", $page ['id'] );
			
			}
		
		}
		
		/*if (intval ( PAGE_ID ) != 0) {
		 require_once (LIBSPATH . "simplehtmldom/simple_html_dom.php");
		 $html = str_get_html ( $layout );
		 foreach ( $html->find ( 'div.module[mw_params_encoded="edit_tag"]' ) as $checkbox ) {
		 //var_Dump($checkbox);
		 $re1 = $checkbox->module_id;
		 $style = $checkbox->style;
		 $re2 = $checkbox->mw_params_module;
		 $tag1 = "<microweber ";
		 $tag1 = $tag1 . "module=\"{$re2}\" ";
		 $tag1 = $tag1 . "module_id=\"{$re1}\" ";
		 $tag1 = $tag1 . "style=\"{$style}\" ";
		 $tag1 .= " />";
		 //p($tag1);
		 $checkbox->outertext = $tag1;
		 $html->save ();

		 }
		 $layout = $html->save ();
		 }
		 */
		//	$this->core_model->cacheWriteAndEncode ( $layout, $function_cache_id, $cache_group );
		

		//echo memory_get_usage() . "\n"; // 36640
		/*$cache_id =  md5 ( $layout ) . md5 ( serialize ( $options ) );
		 $cache_group = 'blocks/'.DIRECTORY_SEPARATOR.intval(PAGE_ID).DIRECTORY_SEPARATOR.'';

		 */
		
		if (($cache_content) == false) {
		
		//return $cache_content;
		

		//	$cache_content = $this->core_model->cacheGetContentAndDecode ( $cache_id, $cache_group );
		

		}
		
		static $mem = array ();
		static $mem2 = array ();
		$layout_md5 = md5 ( $layout );
		$options_md5 = md5 ( serialize ( $options ) );
		$check = $layout_md5 . $options_md5;
		if ($mem ["{$check}"] != false) {
			return $mem [$check];
		}
		//var_dump( $this->$parse_memory);
		//$layout = str_ireplace ( '<mw', '<microweber', $layout );
		$layout = $this->core_model->replace_in_long_text ( '<mw', '<microweber', $layout, $use_normal_replace = true );
		
		$v = $layout;
		
		//	$tags1 = $this->core_model->extractTags ( $v, '*', $selfclosing = true, $return_the_entire_tag = true, $charset = 'UTF-8' );
		

		if (strstr ( $layout, '<microweber' ) == true) {
		
		//$layout = $this->parseMicrwoberTags ( $layout, $options );
		

		}
		
		if (strstr ( $layout, '<mw' ) == true) {
			//$layout = $this->parseMicrwoberTags ( $layout, $options );
		}
		
		if (strstr ( $layout, '<nomw' ) == true) {
			$relations = array ();
			$tags = $this->core_model->extractTags ( $layout, 'nomw', $selfclosing = false, $return_the_entire_tag = true, $charset = 'UTF-8' );
			//	p($tags);
			$matches = $tags;
			$txt_to_replace_back = array ();
			if (! empty ( $matches )) {
				//
				foreach ( $matches as $m ) {
					
					$hash = md5 ( $m ['full_tag'] );
					$hash = 'replace_back_' . $hash;
					
					$txt_to_replace_back [$hash] = $m ['full_tag'];
					
					$layout = str_replace ( $m ['full_tag'], $hash, $layout );
				
				}
			}
		}
		
		//		if (strstr ( $layout, '<block' ) == true) {
		//
		//			$editmode = $this->core_model->is_editmode ();
		//
		//			$relations = array ();
		//			$tags = $this->core_model->extractTags ( $layout, 'block', $selfclosing = true, $return_the_entire_tag = true, $charset = 'UTF-8' );
		//
		//			$matches = $tags;
		//			if (! empty ( $matches )) {
		//				//
		//				foreach ( $matches as $m ) {
		//
		//					//
		//
		//
		//					if ($m ['tag_name'] == 'block') {
		//
		//						$attr = $m ['attributes'];
		//
		//						if ($attr ['id'] != '') {
		//
		//							$attr ['id'] = trim ( $attr ['id'] );
		//
		//							$is_global = $attr ['global'];
		//
		//							if ($is_global == false) {
		//								$is_global2 = $attr ['rel'];
		//								if (trim ( $is_global2 ) == 'global') {
		//									$is_global = true;
		//								}
		//							}
		//
		//							if ((PAGE_ID != false) and $is_global == false) {
		//								$try_file = TEMPLATE_DIR . 'blocks/' . PAGE_ID;
		//								$try_file = normalize_path ( $try_file );
		//								$try_file .= $attr ['id'] . '.php';
		//
		//								if (is_file ( $try_file )) {
		//
		//								} else {
		//									$try_file = TEMPLATE_DIR . 'blocks/' . $attr ['id'] . '.php';
		//								}
		//							} else {
		//								$try_file = TEMPLATE_DIR . 'blocks/' . $attr ['id'] . '.php';
		//							}
		//
		//							$try_file_default = TEMPLATE_DIR . 'blocks/default.php';
		//
		//							if (is_file ( $try_file ) == false) {
		//
		//								$is_admin = is_admin ();
		//
		//								if ($is_admin == true) {
		//
		//									$dir = dirname ( $try_file );
		//									if (is_dir ( $dir ) == false) {
		//										@mkdir_recursive ( $dir );
		//									}
		//									if (! copy ( $try_file_default, $try_file )) {
		//										@touch ( $try_file );
		//										//echo "failed to copy $file...\n";
		//									}
		//
		//								}
		//							}
		//
		//							if (is_file ( $try_file ) == true) {
		//								$arrts = array ();
		//								foreach ( $attr as $att => $at ) {
		//									$this->template [$att] = ($at);
		//
		//									$arrts [$att] = ($at);
		//								}
		//
		//								$this->template ['params'] = $arrts;
		//								$this->load->vars ( $this->template );
		//								$module_file = $this->load->file ( $try_file, true );
		//
		//								/*	if ($editmode == true) {
		//
		//									$edtid_hash = base64_encode ( $m ['full_tag'] );
		//									if (strval ( $module_file ) != '') {
		//										$module_file = '<div class="editblock"  id="' . $attr ['id'] . '">' . $module_file . '</div>';
		//									} else {
		//										$module_file = false;
		//									}
		//
		//								} else {
		//									if (strval ( $module_file ) != '') {
		//										$module_file = '<div class="editblock" id="' . $attr ['id'] . '">' . $module_file . '</div>';
		//									}
		//								}*/
		//
		//								if (strval ( $module_file ) != '') {
		//
		//									if ($is_global == true) {
		//										$str123 = ' rel="global" ';
		//
		//									} else {
		//										$str123 = false;
		//									}
		//
		//									$module_file = '<div class="editblock" ' . $str123 . '   id="' . $attr ['id'] . '">' . $module_file . '</div>';
		//								} else {
		//									//$module_file = false;
		//									$module_file = '<div class="editblock" ' . $str123 . '   id="' . $attr ['id'] . '">' . '</div>';
		//
		//								}
		//
		//								if (strstr ( $module_file, '<block' ) == true) {
		//									$module_file = self::parseMicrwoberTags ( $module_file, $options );
		//								}
		//
		//								$layout = str_replace_count ( $m ['full_tag'], $module_file, $layout, 1 );
		//								//$layout = str_replace ( $m ['full_tag'], '', $layout );
		//								$layout = str_replace_count ( '</block>', '', $layout, 1 );
		//								//$layout = str_replace ( '</microweber>', '', $layout );
		//
		//
		//							}
		//
		//						}
		//
		//					}
		//
		//				}
		//
		//			}
		//
		//		}
		//
		//p($layout);
		

		if (strstr ( $layout, '<microweber' ) == true) {
			
			$editmode = $this->core_model->is_editmode ();
			
			$relations = array ();
			$tags = $this->core_model->extractTags ( $layout, 'microweber', $selfclosing = true, $return_the_entire_tag = true, $charset = 'UTF-8' );
			//	p($tags);
			$matches = $tags;
			if (! empty ( $matches )) {
				
				//
				foreach ( $matches as $m ) {
					
					if ($m ['tag_name'] == 'microweber') {
						
						$attr = $m ['attributes'];
						
						if ($attr ['module'] != '') {
							
							$mmd5 = md5 ( $attr ['module'] );
							$check_if_uninstalled = MODULES_DIR . '_system/' . $mmd5 . '.php';
							
							if (is_dir ( MODULES_DIR . '_system/' ) == false) {
								touch ( MODULES_DIR . '_system/' );
							}
							if (is_file ( $check_if_uninstalled ) == true) {
								//	$attr ['module'] = false;
							}
						}
						
						if (strval ( $attr ['module'] ) == '') {
							$attr ['module'] = 'non_existing';
						}
						
						if ($attr ['module'] != '') {
							
							$attr ['module'] = trim ( $attr ['module'] );
							$attr ['module'] = str_replace ( '\\', '/', $attr ['module'] );
							$attr ['module'] = reduce_double_slashes ( $attr ['module'] );
							
							$try_file1 = MODULES_DIR . '' . $attr ['module'] . '.php';
							
							$try_file = MODULES_DIR . 'modules/' . $attr ['module'] . '.php';
							
							$try_file_db_file = MODULES_DIR . $attr ['module'] . '_db.php';
							$try_file_db_file = normalize_path ( $try_file_db_file, false );
							//p($try_file_db_file);
							if (is_file ( $try_file_db_file ) == true) {
								$this->init_model->db_setup_from_file ( $try_file_db_file );
							}
							
							if ($options ['admin'] == true) {
								$try_file1 = MODULES_DIR . 'admin/' . $attr ['module'] . '.php';
								$try_filefront = MODULES_DIR . '' . $attr ['module'] . '.php';
								
								if (strstr ( $attr ['module'], 'admin' ) == false) {
									
									$try_file1 = MODULES_DIR . 'admin/' . $attr ['module'] . '.php';
								} else {
									$try_file1 = MODULES_DIR . '' . $attr ['module'] . '.php';
								}
							
							}
							$try_file1 = normalize_path ( $try_file1, false );
							//$a = is_file ( $try_file1 );
							if ($attr ['file']) {
								$attr ['view'] = $attr ['file'];
							}
							
							if ($attr ['view']) {
								$module_view_file = ACTIVE_TEMPLATE_DIR . $attr ['view'];
								$module_view_file = normalize_path ( $module_view_file, false );
								// p($module_view_file);
								$is_admin = is_admin ();
								if (is_file ( $module_view_file ) == false) {
									if ($is_admin == true) {
										
										$try_config_file = MODULES_DIR . '' . $attr ['module'] . '_config.php';
										
										$dir = dirname ( $module_view_file );
										if (is_dir ( $dir ) == false) {
											mkdir_recursive ( $dir );
										}
										
										if (copy ( $try_file1, $module_view_file )) {
											$try_file1 = $module_view_file;
										}
									
									}
								} else {
									$try_file1 = $module_view_file;
								}
							
							}
							
							if (is_file ( $try_file1 ) == false) {
								
								if (is_file ( $try_file ) == true) {
									$try_config_file = DEFAULT_TEMPLATE_DIR . 'modules/' . $attr ['module'] . '_config.php';
								} else {
									if ($options ['admin'] == true) {
										if (strstr ( $attr ['module'], 'admin' ) == false) {
											
											$try_file = MODULES_DIR . 'admin/' . '' . 'default' . '.php';
										} else {
											$try_file = MODULES_DIR . '' . '' . 'default' . '.php';
										}
									
									}
								}
								
								$is_admin = is_admin ();
								
								if ($is_admin == true) {
									$try_file1 = $try_filefront;
									$try_config_file = MODULES_DIR . '' . $attr ['module'] . '_config.php';
									
									$dir = dirname ( $try_file1 );
									if (is_dir ( $dir ) == false) {
										mkdir_recursive ( $dir );
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
								$try_config_file = MODULES_DIR . '' . $attr ['module'] . '_config.php';
							
							}
							if ($error == true) {
								$try_file1 = MODULES_DIR . 'non_existing.php';
								$error = false;
							}
							//	p($try_file1);
							

							if (($attr ['module_id']) == true) {
								$mod_id = $attr ['module_id'];
							} else {
								//$mod_id = false;
								$mod_id = $attr ['module'];
								$mod_id = str_replace ( '/', '_', $mod_id );
								$mod_id = str_replace ( '\\', '_', $mod_id );
								$mod_id = str_replace ( '-', '_', $mod_id );
								$attr ['module_id'] = $mod_id;
							}
							
							if (is_file ( $try_file1 ) == true and $error == false) {
								$arrts = array ();
								foreach ( $attr as $att => $at ) {
									$this->template [$att] = ($at);
									//$this->template [$att] = mw_get_var($at);
									$arrts [$att] = ($at);
								}
								$no_edit = false;
								$no_admin = false;
								$check2 = false;
								$mem2_md5_1 = md5 ( serialize ( $m ) );
								//$mem2_md5_2 = md5 ( serialize ( $arrts ) );
								$temp2 = false;
								$check2 = $mem2_md5_1;
								$check2 = strval ( $check2 );
								//
								$temp1 = parse_memory_storage ( $check2 );
								
								if ($temp1 != false) {
									//var_dump ( $temp1 );
									$module_file = $temp1;
								} else {
									//
									$try_config_file = normalize_path ( $try_config_file );
									$try_config_file = rtrim ( $try_config_file, '\\' );
									
									$cache_this = true;
									$force_cache_this = false;
									
									//p($try_config_file);
									$config = false;
									
									if (! is_file ( $try_config_file )) {
										$try_config_file = str_replace ( 'admin', '', $try_config_file );
										$try_config_file = ltrim ( $try_config_file, '\\' );
									}
									
									if (is_file ( $try_config_file )) {
										
										include ($try_config_file);
										
										if (! empty ( $config )) {
											$check_icon = MODULES_DIR . '' . $attr ['module'] . '.png';
											$icon = pathToURL ( $check_icon );
											//p($config);
											

											$config ['icon'] = $icon;
											
											if (! empty ( $config ['options'] )) {
												$this->setup_module_options ( $config ['options'] );
											
											}
											$cache_for_session = false;
											if ($config ['no_cache'] == true) {
												$cache_this = false;
												$do_not_cache_whole_block = true;
												$cache_for_session = true;
											}
											
											if ($config ['cache'] == true) {
												$force_cache_this = true;
												
												$cache_for_session = true;
											}
											
											if ($config ['no_edit'] == true) {
												$no_edit = true;
											
											}
											
											if ($config ['no_admin'] == true) {
												$no_admin = true;
											
											}
										}
									
									}
									
									$config ['url_to_module'] = (MODULES_DIR . '' . $attr ['module'] . '.php');
									$config ['path_to_module'] = normalize_path ( (dirname ( $config ['url_to_module'] )) . '/', true );
									$config ['url_to_module'] = pathToURL ( dirname ( $config ['url_to_module'] ) ) . '/';
									
									$config ['path_to_module_front'] = normalize_path ( str_ireplace ( 'admin', '', $config ['path_to_module'] ), true );
									$config ['url_to_module_front'] = str_ireplace ( 'admin', '', $config ['url_to_module'] );
									//p($config);
									$this->template ['config'] = $config;
									
									if ($arrts ['no_cache'] == true) {
										$cache_this = false;
									}
									
									if ($options ['no_cache'] == true) {
										$cache_this = false;
									}
									if ($config ['no_cache'] == true) {
										$cache_this = false;
									}
									$mod_title = '';
									if ($config ['name'] == true) {
										$mod_title = $config ['name'];
									}
									
									if ($force_cache_this == false) {
										if (strstr ( $attr ['module'], 'admin/' ) == true) {
											$cache_this = false;
										
										}
									}
									
									if ($options ['admin'] == true) {
										$cache_this = false;
									}
									$params_encoded = false;
									
									if ($force_cache_this == true) {
										$cache_this = true;
									}
									//var_dump($force_cache_this);
									

									$is_admin = is_admin ();
									
									if ($arrt ['view']) {
										
										$copy_to = TEMPLATE_DIR . $arrt ['view'];
										if (is_file ( $try_file1 ) and ! is_file ( $copy_to )) {
											if ($is_admin == true) {
												
												$src_to_copy = $try_file1;
												copy ( $src_to_copy, $copy_to );
												$try_file1 = $copy_to;
											}
										}
										
										if (is_file ( $copy_to )) {
											
											$try_file1 = $copy_to;
										
										}
									
									}
									
									if ($cache_this == true) {
										$cache_id = md5 ( $try_file1 ) . md5 ( serialize ( $arrts ) );
										
										if ($cache_for_session == true) {
											$cache_id = md5 ( $try_file1 ) . sess_id () . md5 ( serialize ( $arrts ) );
										}
										
										if ($_POST) {
											$cache_id = $cache_id . md5 ( serialize ( $_POST ) );
										}
										
										$cache_group = 'global/blocks/';
										
										//$cache_content = $this->core_model->cacheGetContentAndDecode ( $cache_id, $cache_group );
										

										if (($cache_content) != false) {
											//var_dump($cache_content);
											$module_file = $cache_content;
										
										} else {
											//	p($arrts);
											

											$this->template ['params'] = $arrts;
											$this->load->vars ( $this->template );
											
											//$module_file = $this->load->file ( $try_file1, true );
											

											$module_file = $this->load->file ( $try_file1, true );
										
		//$this->core_model->cacheWriteAndEncode ( $module_file, $cache_id, $cache_group );
										}
									
									} else {
										//	p($arrts);
										$this->template ['params'] = $arrts;
										$this->load->vars ( $this->template );
										
										//$module_file = $this->load->file ( $try_file1, true );
										$module_file = $this->load->file ( $try_file1, true );
									}
									//$params_encoded = encode_var ( $arrts );
									

									$params_encoded = 'edit_tag';
									$params_module = codeClean ( $arrts ['module'] );
								
								}
								//if (($attr ['module'] != 'header') and ($attr ['module'] != 'footer')) {
								

								//
								$more_attrs2 = '';
								if (! empty ( $arrts )) {
									
									foreach ( $arrts as $arrts_k => $arrts_v ) {
										
										if (strstr ( $arrts ['module'], 'admin' )) {
											if ((strtolower ( $arrts_k ) != 'class') && (strtolower ( $arrts_k ) != 'contenteditable') && (strtolower ( $arrts_k ) != 'style')) {
												$more_attrs2 .= " {$arrts_k}='{$arrts_v}' ";
											} else {
											
											}
										} else {
											if ((strtolower ( $arrts_k ) != 'class') && (strtolower ( $arrts_k ) != 'contenteditable')) {
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
									$params_module_clean = str_replace ( '/', '__', $params_module );
									$params_module_clean = str_replace ( '/', '-', $params_module_clean );
									$params_module_clean = str_replace ( ' ', '-', $params_module_clean );
									//$edtid_hash = base64_encode ( $m ['full_tag'] );
									$edtid_hash = 'edit_tag';
									
									$more_attrs = '';
									
									$more_attrs = " class='mercury-snippet module' ";
									
									$more_attrs2 .= " data____snippet='{$params_module_clean}|{$mod_id}'  data_version='1' ";
									
									$more_attrs2 .= " data-snippet='{$params_module_clean}|{$mod_id}'  contenteditable='false' ";
									
									//  p($more_attrs2);
									//
									//
									if (strval ( $module_file ) != '') {
										
										if ($options ['do_not_wrap'] == true) {
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
									if (strval ( $module_file ) != '') {
										$module_file = '<div ' . $more_attrs2 . ' class="module" ' . $mod_id_tag . '  mw_params_encoded="' . $params_encoded . '" mw_params_module="' . $params_module . '"  >' . $module_file . '</div>';
									}
								}
								//}  ++
								

								$module_file = str_replace ( '<mw', '<microweber', $module_file );
								
								if (strstr ( $params_module, 'source_code' ) == false and $error == false) {
									if (strstr ( $module_file, '<microweber' ) == true and $error == false) {
										$module_file = $this->template_model->parseMicrwoberTags ( $module_file, $options );
									}
								}
								
								//	$layout = str_replace_count ( $m ['full_tag'],htmlentities($m ['full_tag']). $module_file, $layout, 1 );
								$layout = str_replace_count ( $m ['full_tag'], $module_file, $layout, 1 );
								$layout = $this->core_model->replace_in_long_text ( $m ['full_tag'], $module_file, $layout, $use_normal_replace = true );
							
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
		
		if (strstr ( $layout, '<editable' ) == true and $error == false) {
			
			$editmode = $this->core_model->is_editmode ();
			//p($editmode);
			require_once (LIBSPATH . "simplehtmldom/simple_html_dom.php");
			
			$html = str_get_html ( $layout );
			
			foreach ( $html->find ( 'editable' ) as $m ) {
				
				$attr = array ();
				
				$attr ['rel'] = $m->rel;
				$attr ['field'] = $m->field;
				
				$get_global = false;
				if ($editmode == true) {
				
				}
				
				if ($attr ['rel'] == 'global') {
					$attr ['global'] = true;
					$get_global = true;
				} else {
					if ($attr ['global']) {
						unset ( $attr ['global'] );
					}
					$get_global = false;
				}
				
				if ($attr ['rel'] == 'page') {
					$attr ['page'] = PAGE_ID;
				}
				
				if ($attr ['rel'] == 'post') {
					$attr ['post'] = POST_ID;
					if ($attr ['post'] == false) {
						$attr ['post'] = PAGE_ID;
					
		//$attr ['page'] = PAGE_ID;
					}
				}
				if ($attr ['rel'] == 'category') {
					$attr ['category'] = CATEGORY_ID;
				}
				
				if ($attr ['rel'] == 'module') {
					$relate_to_module = true;
				} else {
					$relate_to_module = false;
				}
				
				if ($relate_to_module == false) {
					if ($attr ['page']) {
						$data = get_page ( $attr ['page'] );
					} else if ($attr ['post']) {
						$data = get_post ( $attr ['post'] );
						if ($data == false) {
							$data = get_page ( $attr ['post'] );
						}
					
		//p ( $data );
					//p ( $attr );
					} else if ($attr ['category']) {
						$data = get_category ( $attr ['category'] );
					} else if ($attr ['global']) {
						$get_global = true;
					}
				}
				
				$cf = false;
				$field_content = false;
				
				if ($get_global == true) {
					
					$field_content = $this->core_model->optionsGetByKey ( $attr ['field'], $return_full = false, $orderby = false );
				
				} else {
					if (strstr ( $attr ['field'], 'custom_field_' ) == true) {
						$cf = str_replace ( 'custom_field_', '', $attr ['field'] );
						$field_content = $data ['custom_fields'] [$cf];
					
					} else {
						$field_content = $data [$attr ['field']];
					
					}
				}
				//p( $attr ['field']);
				if (strstr ( $attr ['field'], 'source_code' ) == false) {
					
					if (trim ( $field_content ) == '') {
						$field_content = $m->innertext;
					
		//	$field_content = $m ['contents'];
					} else {
						//$quote_style = ENT_COMPAT [, string $charset = 'UTF-8' ]]
						$field_content = htmlspecialchars_decode ( $field_content );
					
		//$field_content = html_entity_decode ( $field_content, $quote_style = ENT_COMPAT, $charset = 'UTF-8' );
					}
					//	$field_content = html_entity_decode ( $field_content );
					$field_content = htmlspecialchars_decode ( $field_content );
					//	$field_content = $this->template_model->parseMicrwoberTags ( $field_content, $options );
					

					//												$check_divs = strstr ( '<div', $field_content );
					//												if ($check_divs == false) {
					//													$field_content = '<div>' . $field_content . '</div>';
					//												}
					

					//print htmlspecialchars ( $field_content );
					if ($editmode == true) {
						$attrs_to_append = false;
						foreach ( $attr as $at_key => $at_value ) {
							if ($at_key != 'field') {
								$attrs_to_append .= "$at_key='$at_value' ";
							
							}
						}
						//		p($attrs_to_append);
						//	$in = $m->innertext;
						

						$in = "<div id='{$attr['field']}' field='{$attr['field']}' class='edit' {$attrs_to_append}>" . $field_content . '</div>';
						
						$m->outertext = $in;
						
						$layout = $html->save ();
					
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
						$m->outertext = $in;
						
						$layout = $html->save ();
					}
					
					//$layout = str_replace_count ( $m ['full_tag'], $field_content, $layout, 1 );
					//	$layout = str_replace_count ( $m ['full_tag'], "aaaaaaaa", $layout, 1 );
					//$layout = str_replace_count ( 'editable', "aaaaaaaa", $layout, 1 );
					//p($layout);
					//$layout = str_replace ( '<mw', '<microweber', $layout );
					

					//$layout = $this->core_model->replace_in_long_text ( '<mw', '<microweber', $layout, $use_normal_replace = true );
					

					if (strstr ( $layout, '<microweber' ) == true and $error == false) {
						$layout = $this->template_model->parseMicrwoberTags ( $layout, $options );
					}
					if (strstr ( $layout, '<editable' ) == true and $error == false) {
						$layout = $this->template_model->parseMicrwoberTags ( $layout, $options );
					}
				
				} else {
					//$layout = $html->save ();
				}
			
			}
		
		}
		
		//			$relations = array ();
		//			$tags = $this->core_model->extractTags ( $layout, 'editable', $selfclosing = false, $return_the_entire_tag = true, $charset = 'UTF-8' );
		//			$matches = $tags;
		//			if (! empty ( $matches )) {
		//				foreach ( $matches as $m ) {
		//					if ($m ['tag_name'] == 'editable') {
		//						$attr = $m ['attributes'];
		//						$get_global = false;
		//						if ($editmode == true) {
		//
		//						}
		//
		//						if ($attr ['rel'] == 'global') {
		//							$attr ['global'] = true;
		//							$get_global = true;
		//						}
		//
		//						if ($attr ['rel'] == 'page') {
		//							$attr ['page'] = PAGE_ID;
		//						}
		//
		//						if ($attr ['rel'] == 'post') {
		//							$attr ['post'] = POST_ID;
		//							if ($attr ['post'] == false) {
		//								$attr ['post'] = PAGE_ID;
		//								//$attr ['page'] = PAGE_ID;
		//							}
		//						}
		//						if ($attr ['rel'] == 'category') {
		//							$attr ['category'] = CATEGORY_ID;
		//						}
		//
		//						if ($attr ['rel'] == 'module') {
		//							$relate_to_module = true;
		//						} else {
		//							$relate_to_module = false;
		//						}
		//
		//						if ($relate_to_module == false) {
		//							if ($attr ['page']) {
		//								$data = get_page ( $attr ['page'] );
		//							} else if ($attr ['post']) {
		//								$data = get_post ( $attr ['post'] );
		//								if ($data == false) {
		//									$data = get_page ( $attr ['post'] );
		//								}
		//								//p ( $data );
		//							//p ( $attr );
		//							} else if ($attr ['category']) {
		//								$data = get_category ( $attr ['category'] );
		//							} else if ($attr ['global']) {
		//								$get_global = true;
		//							}
		//						}
		//
		//						$cf = false;
		//						$field_content = false;
		//
		//						if ($get_global == true) {
		//
		//							$field_content = $this->core_model->optionsGetByKey ( $attr ['field'], $return_full = false, $orderby = false );
		//
		//						} else {
		//							if (strstr ( $attr ['field'], 'custom_field_' ) == true) {
		//								$cf = str_replace ( 'custom_field_', '', $attr ['field'] );
		//								$field_content = $data ['custom_fields'] [$cf];
		//
		//							} else {
		//								$field_content = $data [$attr ['field']];
		//
		//							}
		//						}
		//
		//						//p($m ['full_tag']);
		//
		//
		//						if (trim ( $field_content ) == '') {
		//
		//							$field_content = $m ['contents'];
		//						} else {
		//							//$quote_style = ENT_COMPAT [, string $charset = 'UTF-8' ]]
		//							$field_content = htmlspecialchars_decode ( $field_content );
		//
		//						//$field_content = html_entity_decode ( $field_content, $quote_style = ENT_COMPAT, $charset = 'UTF-8' );
		//						}
		//						//	$field_content = html_entity_decode ( $field_content );
		//						$field_content = htmlspecialchars_decode ( $field_content );
		//
		//						$attrs_to_append = false;
		//						$field_content = $this->template_model->parseMicrwoberTags ( $field_content, $options = false );
		//
		//						$field_content = str_replace_count ( "</div>\n</div>", "</div>" . $field_content . '</div>', $field_content, 1 );
		//
		//						//												$check_divs = strstr ( '<div', $field_content );
		//						//												if ($check_divs == false) {
		//						//													$field_content = '<div>' . $field_content . '</div>';
		//						//												}
		//
		//
		//						//print htmlspecialchars ( $field_content );
		//						if ($editmode == true) {
		//
		//							foreach ( $attr as $at_key => $at_value ) {
		//								$attrs_to_append .= "$at_key='$at_value' ";
		//							}
		//							//p($attrs_to_append);
		//							$layout = $this->core_model->replace_in_long_text ( $m ['full_tag'], "<div id='{$attr['field']}' class='edit' {$attrs_to_append}>" . $field_content . '</div>', $layout, $use_normal_replace = true );
		//
		//						//$layout = str_replace_count ( $m ['full_tag'], "<div id='{$attr['field']}' class='edit' {$attrs_to_append}>" . $field_content . '</div>', $layout, 1 );
		//						} else {
		//							//$layout = str_replace_count ( $m ['full_tag'], $field_content, $layout, 1 );
		//							//	$layout = str_replace_count ( $m ['full_tag'], "<div id='{$attr['field']}' class='edit'>" . $field_content . '</div>', $layout, 1 );
		//							$layout = $this->core_model->replace_in_long_text ( $m ['full_tag'], "<div id='{$attr['field']}' class='edit'>" . $field_content . '</div>', $layout, $use_normal_replace = true );
		//
		//						}
		//
		//						$layout = str_replace_count ( $m ['full_tag'], $field_content, $layout, 1 );
		//						//	$layout = str_replace_count ( $m ['full_tag'], "aaaaaaaa", $layout, 1 );
		//						//$layout = str_replace_count ( 'editable', "aaaaaaaa", $layout, 1 );
		//						//p($layout);
		//						//$layout = str_replace ( '<mw', '<microweber', $layout );
		//
		//
		//						//$layout = $this->core_model->replace_in_long_text ( '<mw', '<microweber', $layout, $use_normal_replace = true );
		//
		//
		//						if (strstr ( $layout, '<microweber' ) == true and $error == false) {
		//							$layout = $this->template_model->parseMicrwoberTags ( $layout, $options );
		//						}
		//						if (strstr ( $layout, '<editable' ) == true and $error == false) {
		//							$layout = $this->template_model->parseMicrwoberTags ( $layout, $options );
		//						}
		//
		//					}
		//
		//				}
		//
		//			}
		//
		//		}
		

		//{SITE_URL}
		$site_url = site_url ();
		
		//$layout = str_replace ( '{SITE_URL}', $site_url, $layout );
		

		$layout = $this->core_model->replace_in_long_text ( '{SITE_URL}', $site_url, $layout, true );
		$layout = $this->core_model->replace_in_long_text ( '{SITEURL}', $site_url, $layout, true );
		//$layout = str_replace ( '{SITEURL}', $site_url, $layout );
		//$layout = $this->badWordsRemove ( $layout );
		

		if (defined ( 'POST_ID' ) == true) {
			//$layout = str_replace ( '{POST_ID}', POST_ID, $layout );
			$layout = $this->core_model->replace_in_long_text ( '{POST_ID}', POST_ID, $layout, true );
		
		}
		
		if (defined ( 'PAGE_ID' ) == true) {
			//$layout = str_replace ( '{PAGE_ID}', PAGE_ID, $layout );
			$layout = $this->core_model->replace_in_long_text ( '{PAGE_ID}', PAGE_ID, $layout, true );
		}
		
		if (defined ( 'CATEGORY_ID' ) == true) {
			//$layout = str_replace ( '{CATEGORY_ID}', CATEGORY_ID, $layout );
			$layout = $this->core_model->replace_in_long_text ( '{CATEGORY_ID}', CATEGORY_ID, $layout, true );
		
		}
		$layout = str_replace ( '</microweber>', '', $layout );
		
		//$layout = str_replace ( '</microweber>', '</div>', $layout );
		

		if (stristr ( $layout, 'data____snippet' )) {
			//	$layout = str_replace ( 'data____snippet', 'data-snippet', $layout );
		

		}
		
		//	$this->load->vars ( $this->template );
		if (stristr ( $layout, 'content_meta_title' )) {
			
			if (defined ( 'POST_ID' ) == true) {
				$is_content = get_post ( POST_ID );
				$is_content_post = $is_content;
				$layout = str_replace ( '{POST_ID}', POST_ID, $layout );
			}
			
			if ($is_content == false) {
				if (defined ( 'PAGE_ID' ) == true) {
					$is_content = get_page ( PAGE_ID );
					$is_content_page = $is_content;
				}
			}
			
			if ($is_content ['content_meta_title']) {
				$content_meta_title = $is_content ['content_meta_title'];
			} elseif ($is_content ['content_title']) {
				$content_meta_title = codeClean ( $is_content ['content_title'] );
			} else {
				$content_meta_title = $this->core_model->optionsGetByKey ( 'content_meta_title' );
			}
			$layout = str_replace ( '{content_meta_title}', $content_meta_title, $layout );
			
			if ($is_content ['content_meta_keywords']) {
				$content_meta_title = $is_content ['content_meta_keywords'];
			} else {
				$content_meta_title = $this->core_model->optionsGetByKey ( 'content_meta_keywords' );
			}
			$layout = str_replace ( '{content_meta_keywords}', $content_meta_title, $layout );
			
			if ($is_content ['content_meta_description']) {
				$content_meta_title = $is_content ['content_meta_description'];
			} elseif ($is_content ['content_description']) {
				$content_meta_title = codeClean ( $is_content ['content_description'] );
			} elseif ($is_content ['content_body']) {
				$content_meta_title = codeClean ( $is_content ['content_body'] );
			} else {
				$content_meta_title = $this->core_model->optionsGetByKey ( 'content_meta_title' );
			}
			$layout = str_replace ( '{content_description}', $content_meta_title, $layout );
		
		}
		
		if (is_file ( ACTIVE_TEMPLATE_DIR . 'controllers/pre_layout_display.php' )) {
			
			include ACTIVE_TEMPLATE_DIR . 'controllers/pre_layout_display.php';
		
		}
		
		if ((strstr ( $layout, '<editable' ) == true) or (strstr ( $layout, '<mw' ) == true) or (strstr ( $layout, '<microweber' ) == true) or (strstr ( $layout, '<block' ) == true) and $error == false) {
			//	$layout = $this->template_model->parseMicrwoberTags ( $layout, $options );
		}
		
		if ((strstr ( $layout, '<editable' ) == true)) {
			//$layout = $this->template_model->parseMicrwoberTags ( $layout, $options );
		}
		
		if (! empty ( $txt_to_replace_back )) {
			
			foreach ( $txt_to_replace_back as $k => $v ) {
				//	$v = html_entity_decode($v);
				$layout = $this->core_model->replace_in_long_text ( $k, $v, $layout, true );
			
			}
			$layout = $this->core_model->replace_in_long_text ( '<nomw>', '', $layout, true );
			$layout = $this->core_model->replace_in_long_text ( '</nomw>', '', $layout, true );
		}
		
		if (empty ( $relations )) {
			$layout = str_replace ( '</head>', $prepend_to_head . '</head>', $layout );
			//$layout= '<div class="mw_module">'.$layout.'</div>';
			$mem [$check] = $layout;
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
			$mem [$check] = $v;
			return $v;
		}
	
		//p($relations);
	}
	
	function setup_module_options($options_array_from_config) {
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group = 'options' );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			foreach ( $options_array_from_config as $option ) {
				//p ( $option );
				$get_option = array ();
				$get_option ['option_key'] = $option ['param'];
				if ($option ['group']) {
					$get_option ['option_group'] = $option ['group'];
				}
				if ($option ['module']) {
					$get_option ['module'] = $option ['module'];
				}
				$get_option1 = $this->core_model->optionsGetByKey ( $get_option );
				if (empty ( $get_option1 )) {
					$get_option ['name'] = $option ['name'];
					$get_option ['help'] = $option ['help'];
					$get_option ['type'] = $option ['type'];
					$get_option ['name'] = $option ['name'];
					$get_option ['option_value'] = $option ['default'];
					$get_option ['option_value2'] = $option ['values'];
					
					$save = $this->core_model->optionsSave ( $get_option );
				
		//p ( $save );
				

				}
			
			}
			$this->core_model->cacheWriteAndEncode ( 'true', $function_cache_id, $cache_group = 'options' );
			
			return true;
		
		}
	
	}
	
	//$bad_words = $this->core_model->optionsGetByKey ( 'bad_words' );
	

	function badWordsRemove($layout) {
		
		$bad_words = $this->core_model->optionsGetByKey ( 'bad_words' );
		
		if ($bad_words) {
			
			$bad_words_a = explode ( ',', $bad_words );
			foreach ( $bad_words_a as $bad_word ) {
				$rep = str_repeat ( "#", strlen ( $bad_word ) );
				$layout = str_ireplace ( ' ' . $bad_word . ' ', ' ' . $rep . ' ', $layout );
			}
		
		}
		return $layout;
	}
	
	function getAttributes($input) {
		$dom = new DomDocument ();
		$dom->loadHtml ( "<foo " . $input . "/>" );
		$attributes = array ();
		foreach ( $dom->documentElement->attributes as $name => $attr ) {
			$attributes [$name] = $node->value;
		}
		return $attributes;
	}
	
	function getAttribute($attrib, $tag) {
		//get attribute from html tag
		$re = '/' . preg_quote ( $attrib ) . '=([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/is';
		if (preg_match ( $re, $tag, $match )) {
			return urldecode ( $match [2] );
		}
		return false;
	}

}
