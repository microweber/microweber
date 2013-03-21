<?php
require_once "dSendMail2.inc.php";

// Although we can use any SMTP server,
// this class is pre-configured to work with Yahoo, Hotmail (Live) and GMail.

// This works until today (28 / July / 20009)

// Details about each server:
// * GMail: smtp.gmail.com,      465 - Use SSL
// * Yahoo: smtp.mail.yahoo.com, 465 - Use SSL
// * Live:  smtp.live.com,       25  - Use TLS

// IMPORTANT:
// - The ->setFrom() method must be accepted by your server.
// - Example: If you're sending from GMail, you must use your @gmail.com address as "From",
//   or go to GMail/Settings and "Send e-mails using another account". Then you can use it here.

$m = new dSendMail2;
$m->setTo("target@target.com");
$m->setSubject("My sample subject");
$m->setMessage("My sample message");


// Put your e-mail address and uncomment the server you wish to try!
$m->setFrom("myself@mysite.com");
$m->sendThroughGMail  ("username@gmail.com",   "password");
# $m->sendThroughYahoo  ("username@yahoo.com",   "password");
# $m->sendThroughHotMail("username@hotmail.com", "password");

$m->debug = true;
$m->send();
