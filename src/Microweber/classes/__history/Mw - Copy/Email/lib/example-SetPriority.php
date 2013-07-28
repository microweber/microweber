<?php
require_once "dSendMail2.inc.php";

// First of all: DISABLE TIME LIMIT and IGNORE USER ABORT! You really don't want to be interrupted in the middle of this.
set_time_limit(0);
ignore_user_abort(true);

// What is Priority?
// - Outlook Proprietary header which adds an exclamation before the message subject,
//   alerting user that it's important.
// - Doesn't work outside of Microsoft Products.
// - I don't know if it works even in Hotmail.

$m = new dSendMail2;
$m->setTo("target@target.com");
$m->setFrom("myself@mysite.com");
$m->setSubject("My own headers");
$m->setMessage("Here goes my message, through SMTP server.");
$m->setPriority(3); // 1=High, 3=Default, 5=Low
$m->send();
