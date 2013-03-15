<?

$config = array();
$config['name'] = "Comments";
$config['author'] = "Microweber";
$config['ui_admin'] = true;
$config['ui'] = true;                                 


$config['categories'] = "content";
$config['position'] = 5;
$config['version'] = 0.33;


$config['tables'] = array();
$fields_to_add = array();
$fields_to_add[] = array('to_table', 'TEXT default NULL');
$fields_to_add[] = array('to_table_id', 'TEXT default NULL');
$fields_to_add[] = array('updated_on', 'datetime default NULL');
$fields_to_add[] = array('created_on', 'datetime default NULL');
$fields_to_add[] = array('created_by', 'int(11) default NULL');
$fields_to_add[] = array('edited_by', 'int(11) default NULL');
$fields_to_add[] = array('comment_name', 'TEXT default NULL');
$fields_to_add[] = array('comment_body', 'TEXT default NULL');
$fields_to_add[] = array('comment_email', 'TEXT default NULL');
$fields_to_add[] = array('comment_website', 'TEXT default NULL');
$fields_to_add[] = array('is_moderated', "char(1) default 'n'");
$fields_to_add[] = array('is_new', "char(1) default 'y'");

$fields_to_add[] = array('for_newsletter', "char(1) default 'n'");
$fields_to_add[] = array('session_id', 'varchar(255)  default NULL ');
$config['tables']['table_comments'] = $fields_to_add;



$options = array();
$option = array();

$option['option_key'] = 'email_notifcation_on_comment';
$option['option_group'] = 'comments';

$option['name'] = 'Enable email notification on new comment';
$option['help'] = 'If yes it will send you email for every new comment';
$option['option_value'] = 'n';
$option['position'] = '3';
$option['field_type'] = 'dropdown';
$option['field_values'] = array('y' => 'yes', 'n' => 'no');
$config['options'][] = $option;

$option = array();

$option['option_key'] = 'enable_comments';
$option['option_group'] = 'comments';

$option['name'] = 'Allow people to post comments';
$option['help'] = 'If yes it will allow comments on your site';
$option['option_value'] = 'y';
$option['position'] = '5';
$option['field_type'] = 'dropdown';
$option['field_values'] = array('y' => 'yes', 'n' => 'no');
$config['options'][] = $option;
 

 
 

 