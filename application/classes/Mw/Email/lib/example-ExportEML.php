<?php
require_once "dSendMail2.inc.php";

// First of all: DISABLE TIME LIMIT and IGNORE USER ABORT! You really don't want to be interrupted in the middle of this.
set_time_limit(0);
ignore_user_abort(true);

// Why would you want to export something to EML???!?
// 1. To save an EXACTLY copy of messages you sent (database? remember, they are TXT files)
// 2. To open these copies in your outlook
// 3. For fun.

$m = new dSendMail2;
$m->setTo("target@target.com");
$m->setFrom("myself@mysite.com");
$m->setSubject("This is an EML Export sample");
$m->setMessage(
	"Here goes my message.<br />".
	"I can use all class features, like embedding images, attaching files, etc..<br />".
	"<br />"
);

// You don't need to SEND message in order to export it..
$emlContents = $m->exportEML();
file_put_contents("output_message.eml", $emlContents);
