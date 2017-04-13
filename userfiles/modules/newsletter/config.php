<?php
$config = array();
$config['name'] = "Newsletter";
$config['author'] = "Microweber";
$config['ui'] = true;
$config['ui_admin'] = true;
$config['categories'] = "marketing";
$config['position'] = 55;
$config['version'] = 0.1;

$config['tables'] = array(

    "newsletter_subscribers" => array(
        'id' => "integer",
        'name' => "text",
        'email' => "text",
        'created_at' => "dateTime",
        'confirmation_code' => "text",
        'is_subscribed' => "integer"
    ),

    "newsletter_campaigns" => array(
        'id' => "integer",
        'name' => "text",
        'subject' => "text",
        'from_name' => "text",
        'from_email' => "text",
        'created_at' => "dateTime",
        'list_id' => "integer",
        'is_done' => "integer"
    ),

    "newsletter_campaigns_send_log" => array(
        'id' => "integer",
        'campaign_id' => "integer",
        'subscriber_id' => "integer",
        'created_at' => "dateTime",
        'is_done' => "integer"
    )
);