<?php

class Mailaccounts extends Controller {

	function __construct()
	{
		parent::Controller();
		require_once(APPPATH.'controllers/default_constructor.php');
		if($this->session->userdata('user') == false){
			redirect('index/login');
		}
		$table = TABLE_PREFIX.'cacaomail_mail_accounts';
		$this->load->scaffolding($table);
	}

	function index()
	{
		$this->template ['functionName'] = strtolower(__FUNCTION__);
		$this->load->vars ( $this->template );


		$layout = $this->load->view('layout', true,true);
		$primarycontent = '';
		$secondarycontent = '';

		$database_data = $this->cacaomail_model->getMailAccounts();
		$this->template ['database_data'] = $database_data;


		$this->load->vars ( $this->template );
		$primarycontent = $this->load->view('mailaccounts/index', true,true);
		$secondarycontent = $this->load->view('mailaccounts/sidebar', true,true);
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
			$save = $this->cacaomail_model->saveMailAccounts($_POST);
			redirect('mailaccounts');
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
			$form_values = $this->cacaomail_model->getMailAccounts(array('id' => $the_id), 1);
			$form_values = $form_values[0];
			$this->template ['form_values'] = $form_values;


		} else{
			//$this->template ['form_values'] = false;
		}

		$this->template ['form_values']['account_groups'] = $this->cacaomail_model->getMailAccountsGroups();



		$this->load->vars ( $this->template );
		$layout = $this->load->view('layout', true,true);
		$primarycontent = '';
		$secondarycontent = '';



		$primarycontent = $this->load->view('mailaccounts/edit', true,true);
		$secondarycontent = $this->load->view('mailaccounts/sidebar', true,true);
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
			$this->cacaomail_model->deleteMailAccounts(array('id'=> intval($the_id)));
		}
		redirect('mailaccounts');
	}






}
