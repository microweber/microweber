<?php

// SAVE GROUP
api_expose_admin('calendar_save_group');

function calendar_save_group($params)
{
	$save = [];

	if (isset($params['id'])) {
		$save['id'] = $params['id'];
	}

	if (isset($params['title'])) {
		$save['title'] = $params['title'];
	}

	if (! $save) {
		return;
	}

	return db_save("calendar_groups", $save);
}

function calendar_get_groups($params = false)
{
	return db_get("calendar_groups", $params);
}

// DELETE GROUP
api_expose_admin('calendar_delete_group');

function calendar_delete_group($params = false)
{
	if (! isset($params['id'])) {
		return 'Error';
	}

	return db_delete("calendar_groups", intval($params['id']));
}