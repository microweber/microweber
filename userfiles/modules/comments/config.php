<?

$config = array();
$config['name'] = "Comments";
$config['author'] = "Microweber";
$config['ui_admin'] = true;
$config['ui'] = true;                                 


$config['categories'] = "content";
$config['position'] = 3;
$config['version'] = 0.3;


$config['tables'] = array();
 

	$fields_to_add = array();
	$fields_to_add[] = array('title', 'TEXT default NULL');
	$fields_to_add[] = array('is_active', "char(1) default 'y'");
	$fields_to_add[] = array('to_table_id', 'int(11) default NULL');
	$fields_to_add[] = array('to_table', 'varchar(350)  default NULL ');
	$fields_to_add[] = array('updated_on', 'datetime default NULL');
	$fields_to_add[] = array('created_on', 'datetime default NULL');
	$fields_to_add[] = array('price', 'float default NULL');
	$fields_to_add[] = array('currency', 'varchar(33)  default NULL ');
	$fields_to_add[] = array('session_id', 'varchar(255)  default NULL ');
	$fields_to_add[] = array('qty', 'int(11) default NULL');
	$fields_to_add[] = array('other_info', 'TEXT default NULL');
	$fields_to_add[] = array('order_completed', "char(1) default 'n'");
	$fields_to_add[] = array('order_id', 'varchar(255)  default NULL ');
	$fields_to_add[] = array('skip_promo_code', "char(1) default 'n'");
	$fields_to_add[] = array('created_by', 'int(11) default NULL');
	$fields_to_add[] = array('custom_fields_data', 'TEXT default NULL');
	//$config['tables']['table_comments'] = $fields_to_add;