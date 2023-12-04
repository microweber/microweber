
<?php must_have_access(); ?>

<?php
if (!isset($params['id'])) {
    echo 'You must provide campaign id';
    return;
}

$campaign = newsletter_get_campaign($params['id']);
$template = newsletter_get_template(array("id"=>$campaign['email_template_id']));
$subscribers = newsletter_get_subscribers_for_list($campaign['list_id']);
$sender = newsletter_get_sender(array("id"=>$campaign['sender_account_id']));

if (empty($sender)) {
	throw new Exception('Campaign sender is empty.');
}

//var_dump($subscribers);
//var_dump($template);
//var_dump($sender);
//var_dump($list);
?>

Name: <?php print ($campaign['name']); ?> <br />
Subject: <?php print ($campaign['subject']); ?> <br />
From name: <?php print ($campaign['from_name']); ?> <br />
List id: <?php print ($campaign['list_id']); ?> <br />

<hr />
Sending to subscribers... <br /><br />
<b>
<?php

foreach($subscribers as $subscriber) {

	$newsletterMailSender = new \Newsletter\Senders\NewsletterMailSender();
	$newsletterMailSender->setCampaign($campaign);
	$newsletterMailSender->setSubscriber($subscriber);
	$newsletterMailSender->setSender($sender);
	$newsletterMailSender->setTemplate($template);

	$sendMailResponse = $newsletterMailSender->sendMail();

	echo 'Subscriber: ' . $subscriber['name'] . ' (' . $subscriber['email'] . ') <br />';

	if ($sendMailResponse['success']) {
		echo '<font style="color:green;">' . $sendMailResponse['message'] . '</font>';
        newsletter_campaigns_send_log($campaign['id'], $subscriber['id']);

	} else {
		echo '<font style="color:red;">' . $sendMailResponse['message'] . '</font>';
	}
	echo '<br />';
}
?>
</b>
