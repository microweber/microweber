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
function newsletter_get_senders() {

    $get = \MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount::all();
    if ($get) {
        return $get->toArray();
    }
    return [];
}

api_expose('newsletter_delete_sender');
function newsletter_delete_sender($params) {
	if (isset($params['id'])) {
		$table = 'newsletter_sender_accounts';
		$id = $params['id'];
		return db_delete($table, $id);
	}
}

api_expose('newsletter_test_sender');
function newsletter_test_sender($newSender) {
	if (isset($newSender['id'])) {
		$id = $newSender['id'];

		if (empty($newSender['to_email'])) {
			echo '<b>Please, fill send email to.</b>';
			return;
		}

		$campaign = array();
		$campaign['subject'] = 'Campaing subject';
		$campaign['name'] = 'Campaing name';

		$template = array();
		$template['text'] = 'This is an example newsletter test email.';

		$subscriber = array();
		$subscriber['name'] = 'Subscriber name';
		$subscriber['email'] = $newSender['to_email'];

		$sender = new \Newsletter\Senders\NewsletterMailSender();
		$sender->setCampaign($campaign);
		$sender->setSubscriber($subscriber);
		$sender->setSender($newSender);
		$sender->setTemplate($template);

		return $sender->sendMail();

	}
}
