<?php

//error_Log('LOADED plugins/links/links_model.php');

class links_model extends Model {
	public $db_tables;
	
	function __construct() {
		parent::Model ();
		$this->db_tables = array('affiliate_links' => TABLE_PREFIX . 'affiliate_links');
	}
}
