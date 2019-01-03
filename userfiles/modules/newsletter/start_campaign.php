<?php only_admin_access(); ?>
<?php include 'providers/NewsletterMailSender.php'; ?>
<?php 
if (isset($params['id'])) {
    $campaign = newsletter_get_campaign($params['id']);
}
$list = newsletter_get_list($campaign['list_id']);
$template = newsletter_get_template(array("id"=>$list['success_email_template_id']));
$subscribers = newsletter_get_subscribers_for_list($campaign['list_id']);
$sender = newsletter_get_sender(array("id"=>$campaign['sender_account_id']));

if (empty($sender)) {
	throw new Exception('Campaign sender is empty.');
}

//var_dump($subscribers);
//var_dump($template);
var_dump($sender);
//var_dump($list);
?>

Name: <?php print ($campaign['name']); ?> <br />
Subject: <?php print ($campaign['subject']); ?> <br />
From name: <?php print ($campaign['from_name']); ?> <br />
List id: <?php print ($campaign['list_id']); ?> <br />

<br />
Message:
<br />
<?php 
echo $template['text'];
?>
<br />
<br />
<?php
foreach($subscribers as $subscriber) {
	$newsletterMailSender = new NewsletterMailSender();
	$newsletterMailSender->setCampaign($campaign);
	$newsletterMailSender->setSubscriber($subscriber);
	$newsletterMailSender->setSender($sender);
	$newsletterMailSender->setTemplate($template);
	echo $newsletterMailSender->sendMail();
	
}
?>