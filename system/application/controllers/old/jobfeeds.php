<?php



class Jobfeeds extends Controller {

	function __construct()
	{
		parent::Controller();
		require_once(APPPATH.'controllers/default_constructor.php');
		if($this->session->userdata('user') == false){
			redirect('index/login');
		}
	}

	function index()
	{
		$this->template ['functionName'] = strtolower(__FUNCTION__);
		$this->load->vars ( $this->template );


		$layout = $this->load->view('layout', true,true);
		$primarycontent = '';
		$secondarycontent = '';

		$database_data = $this->cacaomail_model->getJobFeeds();
		$this->template ['database_data'] = $database_data;


		$this->load->vars ( $this->template );
		$primarycontent = $this->load->view('jobfeeds/index', true,true);
		$secondarycontent = $this->load->view('jobfeeds/sidebar', true,true);
		$layout = str_ireplace('{primarycontent}', $primarycontent, $layout);
		$layout = str_ireplace('{secondarycontent}', $secondarycontent, $layout);
		//$this->load->view('welcome_message');

		$this->output->set_output($layout);
	}


	function edit()
	{
		$this->template ['functionName'] = strtolower(__FUNCTION__);
		$this->load->vars ( $this->template );

		if($_POST){
			$save = $this->cacaomail_model->saveJobfeeds($_POST);
			redirect('jobfeeds');
		}

		$segs = $this->uri->segment_array();
		$the_id = false;
		foreach ($segs as $segment)
		{
			if(stristr($segment, 'id:') == true){
				$the_id = $segment;
				$the_id = substr($the_id, 3, strlen($the_id));
			}
		}
		if(intval($the_id) != 0){
			$form_values = $this->cacaomail_model->getJobfeeds(array('id' => $the_id), 1);
			$form_values = $form_values[0];

		}

		$feed_groups = $this->cacaomail_model->getJobfeedsGroups();
		//	var_dump($feed_groups);
		$form_values['groups'] = $feed_groups ;



		$this->template ['form_values'] = $form_values;
		$this->load->vars ( $this->template );
		$layout = $this->load->view('layout', true,true);
		$primarycontent = '';
		$secondarycontent = '';

		$primarycontent = $this->load->view('jobfeeds/edit', true,true);
		$secondarycontent = $this->load->view('jobfeeds/sidebar', true,true);
		$layout = str_ireplace('{primarycontent}', $primarycontent, $layout);
		$layout = str_ireplace('{secondarycontent}', $secondarycontent, $layout);
		//$this->load->view('welcome_message');

		$this->output->set_output($layout);
	}

	function delete()
	{
		$this->template ['functionName'] = strtolower(__FUNCTION__);
		$this->load->vars ( $this->template );
		$segs = $this->uri->segment_array();
		$the_id = false;
		foreach ($segs as $segment)
		{
			if(stristr($segment, 'id:') == true){
				$the_id = $segment;
				$the_id = substr($the_id, 3, strlen($the_id));
			}
		}

		if(intval($the_id) != 0){
			$this->cacaomail_model->deleteJobfeeds(array('id'=> intval($the_id)));
		}
		redirect('jobfeeds');
	}




}
?>