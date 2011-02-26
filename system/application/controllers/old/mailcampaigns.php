<?php



class Mailcampaigns extends Controller {

	function __construct()
	{
		parent::Controller();
		require_once(APPPATH.'controllers/default_constructor.php');
		if(CI::library('session')->userdata('user') == false){
			redirect('index/login');
		}
	}

	function index()
	{
		$this->template ['functionName'] = strtolower(__FUNCTION__);
		$this->load->vars ( $this->template );


		$layout = CI::view('layout', true,true);
		$primarycontent = '';
		$secondarycontent = '';

		$database_data = $this->cacaomail_model->getMailCampaigns();
		$this->template ['database_data'] = $database_data;


		$this->load->vars ( $this->template );
		$primarycontent = CI::view('mailcampaigns/index', true,true);
		$secondarycontent = CI::view('mailcampaigns/sidebar', true,true);
		$layout = str_ireplace('{primarycontent}', $primarycontent, $layout);
		$layout = str_ireplace('{secondarycontent}', $secondarycontent, $layout);
		//CI::view('welcome_message');

		CI::library('output')->set_output($layout);
	}


	function edit()
	{
		$this->template ['functionName'] = strtolower(__FUNCTION__);
		$this->load->vars ( $this->template );

		if($_POST){
			$save = $this->cacaomail_model->saveMailCampaigns($_POST);
			redirect('mailcampaigns');
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
			$form_values = $this->cacaomail_model->getMailCampaigns(array('id' => $the_id), 1);
			$form_values = $form_values[0];
			$this->template ['form_values'] = $form_values;
			//$mailist_groups_titles = $this->cacaomail_model->getMailAccountsGroups();
			//$this->template ['form_values']['mailist_groups_titles'] = $mailist_groups_titles;
		}
		$mailist_account_groups = $this->cacaomail_model->getMailAccountsGroups();
		$this->template ['form_values']['mail_accounts_groups'] = $mailist_account_groups ;
		$mailist_account_single = $this->cacaomail_model->getMailAccounts();
		$this->template ['form_values']['mail_accounts_singles'] = $mailist_account_single;

		$temp = $this->cacaomail_model->getJobfeedsGroups();
		$this->template ['form_values']['mail_lists_groups'] = $temp ;
		
		$temp = $this->cacaomail_model->getJobfeeds();
		$this->template ['form_values']['mail_lists_singles'] = $temp ;

		//`mailists_groups_ids` text,
		//`mailists_single_id` int(11) default NULL,


		$this->load->vars ( $this->template );
		$layout = CI::view('layout', true,true);
		$primarycontent = '';
		$secondarycontent = '';

		$primarycontent = CI::view('mailcampaigns/edit', true,true);
		$secondarycontent = CI::view('mailcampaigns/sidebar', true,true);
		$layout = str_ireplace('{primarycontent}', $primarycontent, $layout);
		$layout = str_ireplace('{secondarycontent}', $secondarycontent, $layout);
		//CI::view('welcome_message');

		CI::library('output')->set_output($layout);
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
			$this->cacaomail_model->deleteMailCampaigns(array('id'=> intval($the_id)));
		}
		redirect('mailcampaigns');
	}




}
?>