<?php
/*
 * LIST FUNCTIONS
 */

api_expose_admin('newsletter_get_list');
function newsletter_get_list($list_id) {
	$data = ['id' => $list_id, 'single' => true];
	$table = "newsletter_lists";

	return db_get($table, $data);
}

api_expose('newsletter_get_lists');
function newsletter_get_lists($params = array()) {
	if (is_string($params)) {
		$params = parse_params($params);
	}
	$params ['table'] = "newsletter_lists";
	return db_get($params);
}

api_expose_admin('newsletter_save_list');
function newsletter_save_list($data) {

	$table = "newsletter_lists";
	return db_save($table, $data);
}

api_expose('newsletter_delete_list');
function newsletter_delete_list($params) {
	if (isset($params ['id'])) {

        \MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\NewsletterSubscribersList::where('list_id', $id)->delete();

        $table = "newsletter_lists";
        $id = $params ['id'];
        return db_delete($table, $id);
	}
}
