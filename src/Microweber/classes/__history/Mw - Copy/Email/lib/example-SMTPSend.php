<?php
require_once "dSendMail2.inc.php";

// First of all: DISABLE TIME LIMIT and IGNORE USER ABORT! You really don't want to be interrupted in the middle of this.
set_time_limit(0);
ignore_user_abort(true);

// Parameters:
// ->sendThroughSMTP($smtp_server, $port=25, $user=false, $pass=false, $ssl=false)

$m = new dSendMail2;
$m->setTo("target@target.com");
$m->setFrom("myself@mysite.com");
$m->setSubject("SMTP Test");
$m->setMessage("Here goes my message, through SMTP server.");

// EXACTLY THE SAME CODE as any other example.. Except for the line below:
$m->sendThroughSMTP("smtp.myserver.com", 25, "my_user", "password");

// Real GMail example:
$m->sendThroughSMTP("smtp.gmail.com", 465, "yourname@gmail.com", "yourpassword", true);

// You can still send mass mails, attachments, embedded images... Do what you want!

$m->send();
