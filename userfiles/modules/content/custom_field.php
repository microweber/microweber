<?  

//p( $params); 




$cf_cfg = array ();
								$cf_cfg ['name'] =  $params['name'];
								$cf_cfg = CI::model('core')->getCustomFieldsConfig ( $cf_cfg );
								
								
								
								
								
							
								
								if (! empty ( $cf_cfg )) {
									$cf_cfg = $cf_cfg [0];
									
									
										if($params['value'] == false){
											
											$params['value'] = $cf_cfg['param_default'];
									
								}
									
												$this->template ['data'] = $cf_cfg;
												$this->template ['params'] = $params;
			$this->load->vars ( $this->template );
			$dir =  dirname(__FILE__).'/views/custom_fields/';
			$dir = normalize_path($dir);
			 $is_file = $dir.$cf_cfg['type'].'.php';
			 if(is_file($is_file)){
				 $is_file1 = $this->load->file ( $is_file, true );
				 print $is_file1;
			 } else {
				 
				 ?>
				 <input name="custom_fields_<? print $cf_cfg['param'] ?>" value="<? print  $params['value']   ?>" type="text">
				 
				 
				<?
			 }
		//	$content_filename = $CI->load->file ( $try_file1, true );
			//
									
									
									
									
									// p($cf_cfg);
								}
								
								
								

?>
 
