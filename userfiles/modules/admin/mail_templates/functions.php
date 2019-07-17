<?php

function save_mail_temlate($params)
{
	only_admin_access();

	return db_save('mail_templates', $params);
}

function get_mail_temlate($params)
{
	return db_get('mail_templates', $params);
}