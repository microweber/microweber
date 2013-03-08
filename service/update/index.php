<?
$mw_site_url = "http://api.microweber.net/";
define('MW_BARE_BONES', 1);
include('../../index.php');
if (isset($_GET['api_function'])) {
	$method_name = trim($_GET['api_function']);
	if (isset($_REQUEST['api_function'])) {
		unset($_REQUEST['api_function']);
	}
	$mw_update_server = new mw_update_server();
	if (method_exists($mw_update_server, $method_name)) {
		$result = $mw_update_server -> $method_name($_REQUEST);
		if($result != false){
			$result = replace_site_vars_back($result);
				print json_encode($result);
		}
 	}

}

class mw_update_server {
	private 	$here;
	private 	$downloads_dir;
	private 	$modules_dir;
	function __construct() {
		$this->here = dirname(__FILE__).DS;
		
		
		$this->downloads_dir = $this->here.'downloads'.DS;
		if(!is_dir($this->downloads_dir)){
		 mkdir_recursive($this->downloads_dir);	
		}
		
		
		$this->modules_dir = $this->here.'modules'.DS;
	 }

	function debug($method, $params) {
		print "Called: <b>" . $method . '</b> with params: ';
		print_r($params);
	}
	function get_download_link($params = false){
			if($params != false){
		$params = parse_params($params);
		} else {
		$params = array();	
		}
		 $to_return = array();
		if(isset($params['module'])){
			 $to_return['modules'] = array();
			$params['module'] = str_replace('..','',$params['module']);
			$list_recources_params = array();
						$list_recources_params['dir_name'] = $this->here.'modules'.DS.$params['module'].DS; //get modules in dir
						$list_recources_params['skip_save'] = 1; //if true skips module install
						 $list_recources_params['skip_cache'] = 1;  
 						
						 $params = modules_list($list_recources_params);
						 if(!empty( $params)){
							foreach( $params as $conf){
								
									if(isset($conf['module_base'])){
										
										
										
										$mod_base =$conf['module_base'];
										$mod_base = normalize_path($mod_base,1);
										
										$zip = new ZipArchive();
										$zip_name = str_replace($this->modules_dir,'',$mod_base);
										if(!isset($conf['version'])){
										$conf['version'] = '0.01';	
										}
										$zip_name =  str_replace(DS,'-',$zip_name);
										$zip_name =  trim($zip_name.$conf['version']).".zip";	
														 
									 
										
										
										
										
										$filename = $this->downloads_dir.$zip_name;
										if(!is_file($filename)){
										if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
											exit("cannot open <$filename>\n");
										}
										
										foreach (glob($mod_base."*") as $file_add) {
   											 $file_add_rel = str_replace($this->modules_dir,'',$file_add);
											// d( $file_add_rel);
   										 	$zip->addFile($file_add,$file_add_rel );
										//	print '<hr>';
										}

									
										//echo "numfiles: " . $zip->numFiles . "\n";
										//echo "status:" . $zip->status . "\n";
										$zip->close();
										}
										 $to_return['modules'][] = dir2url($filename);
									}
								 
							}
						 }
		}
		
		return $to_return;
	}
 
	function get_content($params = false){
		if($params != false){
		$params = parse_params($params);
		} else {
		$params = array();	
		}
		
		
		$here = $this->here;
	
		
		
		$params['is_active'] = 'y';
		$params['content_type'] = 'post';
		
		$data = get_content($params);
		if(!empty($data)){
		 foreach($data as $k=> $item){
			 $cf = get_custom_fields("for=content&for_id=".$item['id']);
			 if($cf != false){
				 $data[$k]['custom_fields'] =  $cf ;
				 
				 
				  $data[$k]['name'] =  $data[$k]['title'];
				 
				 
				 foreach($cf as $cfk => $custom_field){
					
					if(trim($cfk) == 'folder'){
						
						 
						
						
						 $data[$k]['module'] =  str_replace_once('modules/' ,'' ,$custom_field );
						$list_recources_params = array();
						$list_recources_params['dir_name'] = $here.$custom_field; //get modules in dir
						$list_recources_params['skip_save'] = 1; //if true skips module install
												$list_recources_params['skip_cache'] = 1;  

						$list_recources_params['cache_group'] = 'modules/global'; // allows custom cache group
					 
						 $list_recources_params = modules_list($list_recources_params);
						 
						 $data[$k]['resources'] =  $list_recources_params ;
				 
					}
					
					 
					 
				 }
				 
				 
			 }
			 
			  $iu = get_picture($item['id'], $for = 'post', $full = false);
		 
			if($iu != false){
				  $data[$k]['image'] = $data[$k]['icon'] = $iu;
			} else {
				 $data[$k]['image'] = $data[$k]['icon'] = false;
			}
		 }
		}
 		return $data;
	
}
	

	function get_modules($params = false) {
		
		if($params != false){
		$params = parse_params($params);
		} else {
		$params = array();	
		}
	 
		 $params['parent'] = 2;
	$data = $this->get_content($params);
	return $data;
	}

	 

}
