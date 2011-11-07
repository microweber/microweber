<?php
/**
 * KFM plugin class
 */
class kfmPlugin extends kfmObject{
	public $disabled=false;
	public $name='KFM plugin';
	public $title='KFM plugin';
	public $settings=array();
	private $javascript='';
	public $javascript_files=array();
	public $admin_tabs=array();
	function __construct($name){
		global $kfm;
		$this->name=$name;
		$this->title=$this->name;
		$bt=debug_backtrace();
		$this->path=dirname($bt[0]['file']).'/';
		//$this->url=$kfm->setting('kfm_url').'plugins/'.$name.'/';
	}
	
	/**
	 * This function add a setting that can be used by this plugin. This setting will appear in the settings panel
	 */
	function addSetting($name, $definition, $default){
		$this->settings[]=array('name'=>$name,'definition'=>$definition,'default'=>$default);
	}

  function url(){
    global $kfm;
    return $kfm->setting('kfm_url').'plugins/'.$this->name.'/';
  }

	function getJavascript(){
		if($this->disabled)return '';
		$js='';
		if(strlen($this->javascript))$js.=kfm_parse_template($this->javascript);
		if(file_exists($this->path.'plugin.js'))$js.=file_get_contents($this->path.'plugin.js');
		return $js;
	}
	function getJavascriptFiles(){
		$str='';
		foreach($this->javascript_files as $js_file){
			$str.='<script type="text/javascript" src="plugins/'.$this->name.'/'.$js_file.'"></script>'."\n";
		}
		return $str;
	}
	function addJavascript($js){
		if(substr($js,-3,3)=='.js')$this->javascript_files[]=$js;
		else $this->javascript.=$js."\n";
	}

	/**
	 * This function sets the title of the plugin
	 */
	function setTitle($title){
		$this->title=$title;
	}
	
	/**
	 * This function add a tab to the admin section. The file will be the content of this tab
	 */
	function adminTab($file, $options=array()){
		$tab = array("file" => $file);
		$tab["requirements"] = is_array($options) && isset($options['requirements']) ? $options['requirements'] : array();
		if(!isset($tab["requirements"]["user_ids"]))$tab["requirements"]["user_ids"] = array();
		if(is_string($options)){
			if(in_array($options, array('admin_user', 'user_admin', 'admin'))) array_push($tab["requirements"]["user_ids"], 1);
		}
		if(is_array($options)){
			$tab = array_merge($options, $tab);
			if(isset($options["user_id"])) $tab["requirements"]['user_ids'][] = $options["user_id"];
		}
		$this->admin_tabs[]=$tab;
	}
}
