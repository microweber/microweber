<?

$config = array();
$config['name'] = "Ip2Country";
$config['author'] = "Microweber";
$config['ui'] = false;
$config['ui_admin'] = false;
$config['categories'] = "advanced";
$config['position'] = 60;
$config['version'] = 0.3;



$config['tables'] = array();
$fields_to_add = array();
$fields_to_add[] = array('ip', 'TEXT default NULL');
$fields_to_add[] = array('ip_long', 'TEXT default NULL');
$fields_to_add[] = array('country_code', 'TEXT default NULL');
$fields_to_add[] = array('country_name', 'TEXT default NULL');
$fields_to_add[] = array('region', 'TEXT default NULL');
$fields_to_add[] = array('city', 'TEXT default NULL');
$fields_to_add[] = array('latitude', 'TEXT default NULL');
$fields_to_add[] = array('longitude', 'TEXT default NULL');

 
$config['tables']['table_ip2country'] = $fields_to_add;

 