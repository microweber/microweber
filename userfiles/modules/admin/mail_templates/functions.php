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

function get_mail_templates($params = array())
{
	if (is_string($params)) {
		$params = parse_params($params);
	}
	$params['table'] = "mail_templates";
	return db_get($params);
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