<?php

class cron extends CI_Controller {

	function __construct()
	{
		parent::Controller();
		require_once(APPPATH.'controllers/default_constructor.php');
	}

	function index()
	{
		header("Content-type: text/plain");

		$segs = $this->uri->segment_array();

		$cron_group = false;
		$force= false;
		$action= false;
		foreach ($segs as $segment)
		{
			if(stristr($segment, 'g:') == true){
				$cron_group = substr($segment, 2,strlen($segment));
			///	exit($cron_group);
			}
			if(stristr($segment, 'force:true') == true){
				$force= true;
			}
			
		if(stristr($segment, 'a:') == true){
				$action = substr($segment, 2,strlen($segment));
			}
			
		}

		
		$job = $this->core_model->cronjobGetOne($cron_group, $force, $action);

		if(!empty($job)){
			print  "\n\n" .  'Begin - ' . date("Y-m-d H:i:s"). ' - '.$job['cronjob_name'] . "\n";
			$to_exc = '$this->'.$job['model_name'].'->'.$job['function_to_execute'];

			//print $to_exc;
			eval($to_exc.';');

			print "\n" . 'End - ' . date("Y-m-d H:i:s"). ' - '.$job['cronjob_name'] . "\n\n";
		} else{
			print "\n" . 'No jobs - ' . date("Y-m-d H:i:s"). "\n\n";
		}
		//var_dump($job);



		//print 'cron-' . date("Y-m-d H:i:s");
	}

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */