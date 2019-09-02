<?php
event_bind('mw.admin.dashboard.content.3', function ($item) {
	print '<div type="contact_form/dashboard_recent_messages" class="mw-lazy-load-module" id="admin-dashboard-contact-form"></div>';
});

api_expose('get_contact_entry_by_id');
function get_contact_entry_by_id($params)
{
	only_admin_access();
	
	$form_data = mw()->forms_manager->get_entires('single=1&id=' . $params['id']);
	
	return $form_data;
}
