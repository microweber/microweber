<?php
$config = array();
$config['name'] = "Mail Templates";
$config['author'] = "Microweber";
//$config['ui'] = false;
$config['position'] = 100;
//$config['type'] = "mail_templates";

$config['settings']['service_provider'] = [
    \MicroweberPackages\Admin\MailTemplates\MailTemplatesServiceProvider::class
];
