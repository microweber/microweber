
<?php
/* 
 * EMAIL SENDER FUNCTIONS
 */

api_expose_admin('newsletter_get_sender');
function newsletter_get_sender($data) {
	$data = ['id' => $data['id'], 'single' => true];
	$table = "newsletter_sender_accounts";
	return db_get($table, $data);
}

api_expose_admin('newsletter_get_senders');
function newsletter_get_senders($params = array()) {
	if (is_string($params)) {
		$params = parse_params($params);
	}
	$params ['table'] = "newsletter_sender_accounts";
	return db_get($params);
}

api_expose('newsletter_save_sender');
function newsletter_save_sender($data) {
	$table = 'newsletter_sender_accounts';
	$data['allow_html'] = true;
	return db_save($table, $data);
}

api_expose('newsletter_delete_sender');
function newsletter_delete_sender($params) {
	if (isset($params['id'])) {
		$table = 'newsletter_sender_accounts';
		$id = $params['id'];
		return db_delete($table, $id);
	}
}
