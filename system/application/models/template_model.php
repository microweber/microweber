<?php class Template_model extends Model {
	
	function __construct() {
		parent::Model ();
	
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
	
	/**
	 * @desc  Get the template layouts info under the layouts subdir on your active template
	 * @param $options
	 * 		$options ['type'] - 'layout' is the default type if you dont define any. You can define your own types as post/form, etc in the layout.txt file
	 * @return array
	 * @author	Microweber Dev Team
	 * @since Version 1.0
	 */
	function layoutsList($options = false) {
		$this->load->helper ( 'directory' );
		//$path = BASEPATH . 'content/templates/';
		$the_active_site_template = $this->core_model->optionsGetByKey ( 'curent_template' );
		$path = TEMPLATEFILES . '' . $the_active_site_template . '/layouts/';
		//	print $path;
		//exit;
		

		//$map = directory_map ( $path, TRUE );
		$map = directory_map ( $path, TRUE, TRUE );
		//var_dump($map);
		$to_return = array ();
		
		foreach ( $map as $dir ) {
			$filename = $path . $dir . DIRECTORY_SEPARATOR . 'layout.php';
			//var_dump($filename);
			if (is_file ( $filename )) {
				$ext = file_extension ( $filename );
				if ($ext == 'php') {
					
					$filename_no_ext = $file = basename ( $filename, "." . $ext );
					$txt_file = $path . $dir . DIRECTORY_SEPARATOR . $filename_no_ext . '.txt';
					
					if (is_file ( $txt_file )) {
						$fin = @file_get_contents ( $txt_file );
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
							$to_return_temp ['filename'] = $filename;
							$to_return_temp ['dir'] = trim ( $dir );
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
							
							$screens_dir = $path . $dir . DIRECTORY_SEPARATOR . 'screenshots' . DIRECTORY_SEPARATOR;
							if (is_dir ( $screens_dir )) {
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
							}
							
							$to_return [] = $to_return_temp;
						}
					}
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
		$this->load->helper ( 'directory' );
		//$path = BASEPATH . 'content/templates/';
		$the_active_site_template = $this->core_model->optionsGetByKey ( 'curent_template' );
		$path = TEMPLATEFILES . '' . $the_active_site_template . '/layouts/';
		//	print $path;
		//exit;
		

		$filename = $path . $layout_name . DIRECTORY_SEPARATOR . 'layout.php';
		if (is_file ( $filename )) {
			$html = @file_get_contents ( $filename );
			if ($html != '') {
				require_once 'htmlsql-v0.5/htmlsql.class.php';
				require_once ("htmlsql-v0.5/snoopy.class.php");
				
				$wsql = new htmlsql ( );
				
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
		
		$this->load->helper ( 'directory' );
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
						$fin = @file_get_contents ( $filename2 );
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
	 * 		$options ['no_microwber_tags'] - default: false - removes the <microweber> tags
	 * 		$options ['no_remove_div'] - default: false - removes the <div class="remove-on-submit"> tags
	 * 		$options ['get_only_stylesheets_as_csv'] - default: false - return the CSS files urls as csv string
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
			//$res = $this->core_model->fetchDbData ( $data ['to_table'], array (array ('is_active', 'y' ), array ('id', $data ['to_table_id'] ) ), array ('debug' => false, 'cache_group' => false, 'order' => array (array ('id', 'DESC' ) ) ) );
		

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
				$return[] = $file1_url;
			}
			if (is_file ( $file2 ) == true) {
				$return[] = $file2_url;
			} else {
				if (is_file ( $file3 ) == true) {
					$return[] = $file3_url;
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
	
	/**
	 * @desc  Parses the relations array and returns the aprropriate data. Define your custom parser with the 'type' option
	 * @param $data
	 * @param $options
	 * 		$options ['to_table'] - give the table name
	 * 		$options ['to_table_id'] - give the id
	 * 		$options ['type'] 	- if 'content' - get data from the database
	 * 							- no other types defined for now
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
					
				/*$res = $this->core_model->fetchDbData ( $data ['to_table'], 
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
	
	function parseMicrwoberTags($layout, $options = false) {
		$v = $layout;
		if (strstr ( $v, '<microweber>' ) == true) {
			//exit($v);
			$relations = array ();
			// @todo ? whats this  delete it? - > $item ['dynamic_content_relations'] = array ('asd' );
			preg_match_all ( '|<microweber.*?</microweber>|ms', $v, $matches );
			if (! empty ( $matches )) {
				
				foreach ( $matches as $m ) {
					//p ( $m );
					if (! empty ( $m )) {
						foreach ( $m as $n ) {
							if (trim ( $n ) != '') {
								//	
								$orig_n = $n;
								$n = str_replace ( '<microweber>', '', $n );
								$n = str_replace ( '</microweber>', '', $n );
								$n = json_decode ( $n, 1 );
								if (is_array ( $n )) {
									if (! empty ( $n )) {
										$exist = false;
										//	$m = json_decode ( $m );
										foreach ( $relations as $re => $rel ) {
											if ($rel == $n) {
												$exist = true;
												//	var_dump ( $n );
											}
										}
										if ($exist == false) {
											$test = $this->microweberTagsGenerate ( $n, $options );
											$parsed = $this->template_model->parseDynamicRelations ( $n );
											if ($parsed ['content_layout_name'] != '') {
												$prepend_to_head = ' <link rel="stylesheet" href="' . LAYOUTS_URL . $parsed ['content_layout_name'] . 

												'/layout.css" type="text/css" media="all"  />';
												$prepend_to_head = $prepend_to_head . '<link rel="stylesheet" href="' . LAYOUTS_URL . $parsed ['content_layout_name'] . '/styles/' . $parsed ['content_layout_style'] . '" type="text/css" media="all"  />';
											}
											
											$v = str_replace ( $orig_n, $test, $v );
											//	p ( $test );
											$relations [] = $n;
										}
									}
								}
								//	
							}
						}
					}
				}
			
			}
			//p($matches, 0);
		}
		if (empty ( $relations )) {
			$layout = str_ireplace ( '</head>', $prepend_to_head . '</head>', $layout );
			
			return $layout;
		} else {
			return $v;
		}
		//p($relations);
	}

}
