<?php





$table_name = false;
$table_name = TABLE_PREFIX . "cacaomail_mail_campaigns";
$query = CI::db()->query("show tables like '$table_name'");
$query = $query->row_array();
$query = (array_values($query));
if($query[0] != $table_name) {
	$sql = "CREATE TABLE " . $table_name . " (
						id int(11) NOT NULL auto_increment,
						UNIQUE KEY id (id)
						);";
	CI::db()->query($sql);
}


$sql = "show tables like '$table_name'";
$query = CI::db()->query($sql);
$query = $query->row_array();
$query = (array_values($query));

if($query[0] == $table_name) {
	//$columns = $db->fetchAll("show columns from $table_name");
	$sql = "show columns from $table_name";
	$query = CI::db()->query($sql);
	$columns = $query->result_array();


	$exisiting_fields = array();
	foreach ($columns as $fivesdraft) {
		$fivesdraft = array_change_key_case($fivesdraft,CASE_LOWER);
		$exisiting_fields[strtolower($fivesdraft['field'])] = true;
	}






	$fields_to_add = array();
	$fields_to_add[] = array( 'mailists_groups_ids',   "TEXT default NULL");
	$fields_to_add[] = array( 'mailists_single_id',   "int(11) default NULL");



	$fields_to_add[] = array( 'mailaccounts_groups_ids',   "TEXT default NULL");
	$fields_to_add[] = array( 'mailaccounts_single_id',   "int(11) default NULL");



	$fields_to_add[] = array( 'campaign_title',   "TEXT default NULL");
	$fields_to_add[] = array( 'campaign_description',   'TEXT default NULL');
	$fields_to_add[] = array( 'campaign_default_subject',   "TEXT default NULL");
	$fields_to_add[] = array( 'campaign_template_file',   'TEXT default NULL');
	$fields_to_add[] = array( 'campaign_template_file_plain',   'TEXT default NULL');
	$fields_to_add[] = array( 'campaign_attachments',   'TEXT default NULL');
	$fields_to_add[] = array( 'campaign_priority',   'int(11) default 1');
	$fields_to_add[] = array( 'campaign_start_date',   'datetime default NULL');
	$fields_to_add[] = array( 'campaign_end_date',   'datetime default NULL');
	$fields_to_add[] = array( 'campaign_repeat_days',   'int(11) default NULL');
	$fields_to_add[] = array( 'campaign_last_run',   'datetime default NULL');
	$fields_to_add[] = array( 'updated_on',   'datetime default NULL');
	$fields_to_add[] = array( 'is_active',   'int(1) default 1');
	$fields_to_add[] = array( 'campaign_chart_color',   'varchar(1500) default NULL');
	//$fields_to_add[] = array( 'limit_per_day',   'int(100) default NULL');
	//aa
	foreach($fields_to_add as $the_field){
		$sql = false;
		$the_field[0] = strtolower($the_field[0]);
		if ($exisiting_fields[$the_field[0]] != true) {
			$sql = "alter table $table_name add column {$the_field[0]} {$the_field[1]} ";
			CI::db()->query($sql);
		} else {
			$sql = "alter table $table_name modify {$the_field[0]} {$the_field[1]} ";
			CI::db()->query($sql);
		}

	}
}













?>