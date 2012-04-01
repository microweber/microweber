<?php

class M extends Controller {

	function __construct()
	{
		parent::Controller();
		require_once(APPPATH.'controllers/default_constructor.php');
	}

	function index(){
		$segs = $this->uri->segment_array();
		$the_actions = array();
		$the_actions_trigger = false;
		foreach ($segs as $segment)
		{
			if(stristr($segment,'index') == true){
				$the_actions_trigger = true;
			}

			if($the_actions_trigger == true){
				$the_actions[] = $segment;
			}
		}
		$params = array();
		for ($i = 3; $i < count($the_actions); $i++) {
			$params[] = $the_actions[$i];
		}
		$params = join(',',$params);
		//print $params;
		//$exec = '$this->'.$the_actions[1].'->'.$the_actions[2].'('.$params.')';
		//print $exec;
		//eval($exec);
		$this->$the_actions[1]->$the_actions[2]($params);
	}


}

?>