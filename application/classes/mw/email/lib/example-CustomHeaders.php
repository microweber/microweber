<?php
require_once "dSendMail2.inc.php";

// First of all: DISABLE TIME LIMIT and IGNORE USER ABORT! You really don't want to be interrupted in the middle of this.
set_time_limit(0);
ignore_user_abort(true);


$m = new dSendMail2;
$m->setTo("target@target.com");
$m->setFrom("myself@mysite.com");
$m->setSubject("My own headers");
$m->setMessage("Here goes my message, through SMTP server.");

// Here goes your headers:
$m->headers['My-Header'] = "My Value";
$m->headers['Reply-To']  = "myreply@to.com";

$m->send();
