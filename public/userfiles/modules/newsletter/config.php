<?php
$config = array();
$config['name'] = 'Newsletter';
$config['author'] = 'Microweber';
$config['ui'] = true;
$config['ui_admin'] = true;
$config['categories'] = 'marketing';
$config['position'] = 55;
$config['version'] = "2.0";

$config['tables'] = array (

	'newsletter_subscribers' => array (
        'id' => 'integer',
        'name' => 'text',
        'email' => 'text',
        'created_at' => 'dateTime',
        'updated_at' => 'dateTime',
        'confirmation_code' => 'text',
        'is_subscribed' => 'integer'
	),

	'newsletter_campaigns' => array (
        'id' => 'integer',
        'name' => 'text',
        'subject' => 'text',
        'recipients_from' => 'text',
        'delivery_type' => 'text',
        'from_name' => 'text',
        // 'from_email' => 'text',
        'created_at' => 'dateTime',
        'updated_at' => 'dateTime',
        'email_template_id' => 'integer',
        'list_id' => 'integer',
        'sender_account_id' => 'integer',
        'sending_limit_per_day' => 'integer',
        'is_scheduled' => 'integer',
        'scheduled_at' => 'dateTime',
        'scheduled_timezone' => 'text',
        'is_done' => 'integer',
        'status' => 'text',
        'email_content_type' => 'text',
        'email_content_html' => 'text',
        'email_attached_files' => 'text',
        'enable_email_attachments' => 'integer',
        'delay_between_sending_emails' => 'integer',
        'jobs_batch_id' => 'text',
        'jobs_progress' => 'integer',
        'total_jobs'=> 'integer',
        'completed_jobs'=> 'integer',
	),

	'newsletter_campaigns_send_log' => array (
        'id' => 'integer',
        'campaign_id' => 'integer',
        'subscriber_id' => 'integer',
        'created_at' => 'dateTime',
        'updated_at' => 'dateTime',
        'is_sent' => 'integer'
	),

    'newsletter_campaigns_pixel' => array (
        'id' => 'integer',
        'campaign_id' => 'integer',
        'email' => 'text',
        'ip' => 'text',
        'user_agent' => 'text',
        'created_at' => 'dateTime',
        'updated_at' => 'dateTime',
    ),

    'newsletter_campaigns_clicked_link' => array (
        'id' => 'integer',
        'campaign_id' => 'integer',
        'email' => 'text',
        'ip' => 'text',
        'user_agent' => 'text',
        'link' => 'text',
        'created_at' => 'dateTime',
        'updated_at' => 'dateTime',
    ),

	'newsletter_sender_accounts' => array(
		'id' => 'integer',
		'name' => 'text',
		'from_name' => 'text',
		'from_email' => 'text',
		'reply_email' => 'text',
		'created_at' => 'dateTime',
		'account_type' => 'text',

		// Smtp settings
		'smtp_username' => 'text',
		'smtp_password' => 'text',
		'smtp_host' => 'text',
		'smtp_port' => 'text',

		// Mailchimp settings
		'mailchimp_secret' => 'text',

		// Mailgun settings
		'mailgun_domain' => 'text',
		'mailgun_secret' => 'text',

		// Mandrill settings
		'mandrill_secret' => 'text',

		// Sparkpost settings
		'sparkpost_secret' => 'text',

		// Amazon ses settings
		'amazon_ses_key' => 'text',
		'amazon_ses_secret' => 'text',
		'amazon_ses_region' => 'text', // e.g. us-eas

		'account_pass' => 'text',
		'is_active' => 'integer'
	),

	'newsletter_lists' => array(
		'id' => 'integer',
		'name' => 'text',
		'success_email_template_id' => 'integer',
		'success_sender_account_id' => 'integer',
		'unsubscription_sender_account_id' => 'integer',
		'unsubscription_email_template_id' => 'integer',
		'confirmation_email_template_id' => 'integer',
		'confirmation_sender_account_id' => 'integer',
		'created_at' => 'dateTime',
        'updated_at' => 'dateTime'
    ),

	'newsletter_subscribers_lists' => array (
        'id' => 'integer',
        'subscriber_id' => 'integer',
        'list_id' => 'integer',
        'created_at' => 'dateTime',
        'updated_at' => 'dateTime'
    ),

	'newsletter_templates' => array (
        'id' => 'integer',
        'title' => 'text',
        'text' => 'text',
        'json' => 'text',
        'created_at' => 'dateTime',
        'updated_at' => 'dateTime'
	)
);

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\Newsletter'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Newsletter\Providers\NewsletterServiceProvider::class
];
