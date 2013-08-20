<?php
require_once "dSendMail2.inc.php";

// First of all: DISABLE TIME LIMIT and IGNORE USER ABORT! You really don't want to be interrupted in the middle of this.
set_time_limit(0);
ignore_user_abort(true);


$m = new dSendMail2;
$m->setTo("target@target.com");
$m->setFrom("myself@mysite.com");
$m->setSubject(utf8_encode("Título (Title) 3"));
$m->setMessage(utf8_encode("Coração Saudável"));

// Here is your charset. Default is ISO-8859-1 (Latin1)
// Calling this method is optional.
$m->setCharset('UTF-8');

$m->debug = true;
$m->send();
