<?php
class Sql extends Controller {
	
	function __construct() {
		parent::Controller ();
		require_once (APPPATH . 'controllers/default_constructor.php');
	}
	
	function index() {
		$this->template ['functionName'] = strtolower ( __FUNCTION__ );
		/*
		$this->load->vars ( $this->template );
		$layout = $this->load->view ( 'layout', true, true );
		$primarycontent = $this->load->view ( 'me/index', true, true );
		$layout = str_ireplace ( '{primarycontent}', $primarycontent, $layout );
		$this->output->set_output ( $layout );
		*/
		
		
		$sql =<<<STR
	select u.id, u.username from firecms_users u
    	
STR;
		$users = $this->core_model->dbQuery($sql);
		
		for($i=0; $i<count($users);$i++){
			$sql_update = "UPDATE affiliate_users set parent_affil='{$users[$i]['username']}' WHERE parent_affil_id={$users[$i]['id']} ";
			$this->core_model->dbQ($sql_update);
		}
		
				$sql =<<<STR
	select u.id, u.parent_affil_id,u.parent_affil from affiliate_users u
    	
STR;
		$users = $this->core_model->dbQuery($sql);
	p($users);	
		
die;




		$sql =<<<STR
	select c1.username, c1.tier from cosmic_affiliates c1
    	
STR;
		$tiers = $this->core_model->dbQuery($sql);
$ids = array();
	for($i=0; $i < count($tiers);$i++){
		$res = array();
			$uname = htmlentities($tiers[$i]['tier'],ENT_QUOTES);
			$sql_update= "select id from firecms_users WHERE username='$uname' ";
			
			$res = $this->core_model->dbQuery($sql_update);
			
			if(!empty($res))
				$ids[$tiers[$i]['username']] = $res[0]['id'];
			
		}
		
	
$sql_update='';
		foreach($ids as $kol => $val){
			$uname = htmlentities($kol,ENT_QUOTES);
			$sql_update= "UPDATE firecms_users set parent_affil='{$val}' WHERE username='$uname' ";
			
			$this->core_model->dbQ($sql_update);
			
		}
		$sql = 'select id,username, parent_affil from firecms_users';
$res = $this->core_model->dbQuery($sql);
p($res);
	}
}

?>