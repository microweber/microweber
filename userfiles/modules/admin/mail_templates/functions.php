<?php
api_expose('save_mail_template');
function save_mail_template($data)
{
	if (! is_admin()) {
		return;
	}
	$table = "mail_templates";
	return db_save($table, $data);
}

function get_mail_template_by_id($id) {
	
	foreach (get_mail_templates() as $template) {
		if ($template['id'] == $id) {
			return $template;
		}
	}
	
}

function get_mail_templates($params = array())
{
	if (is_string($params)) {
		$params = parse_params($params);
	}
	
	$params['table'] = "mail_templates";
	$templates =  db_get($params);
	
	$typesMap = array();
	if (!empty($templates)) {
		foreach ($templates as $template) {
			$typesMap[] = $template['type'];
		}
	}
	
	$default_mail_templates = normalize_path(MW_PATH  . 'Views/emails');
	$default_mail_templates = scandir($default_mail_templates);
	
	foreach ($default_mail_templates as $template_file) {
		if (strpos($template_file, "blade.php") !== false) {
			
			$template_type = str_replace('.blade.php', false, $template_file);
			$template_name = str_replace('_', ' ', $template_type);
			$template_name = ucfirst($template_name);
			
			if (in_array($template_type, $typesMap)) {
				continue;
			}
			
			$templates[] = array(
				'id'=> $template_file,
				'type' => $template_type,
				'name' => $template_name,
				'subject'=>$template_name,
				'from_name'=> get_option('email_from_name','email'),
				'from_email'=> get_option('email_from','email'),
				'copy_to'=>'',
				'message'=> '',
				'is_default' => true,
				'is_active' => 1
			);
		}
	}
	
	return $templates;
}

api_expose('delete_mail_template');
function delete_mail_template($params)
{
	if (! is_admin()) {
		return;
	}
	if (isset($params['id'])) {
		$table = "mail_templates";
		$id = $params['id'];
		return db_delete($table, $id);
	}
}