<?php
require_once "dSendMail2.inc.php";

// First of all: DISABLE TIME LIMIT and IGNORE USER ABORT! You really don't want to be interrupted in the middle of this.
set_time_limit(0);
ignore_user_abort(true);

// Basics - Setting the message...
$m = new dSendMail2;
$m->setSubject("Mass Mailer Message");
$m->setFrom("nobody@nowhere.com");    // Visible "From:" header. Recipient will see this.
$m->setMessage("I am a sample message..");

// You probably don't want everyone to see your e-mails list..
// So, you'll like to send it as BCC (hidden).

// Tip: Class can recognize multiple destinations like:
// go1@go.com,go2@go.com,go2@go.com
// go1@go.com;go2@go.com;go3@go.com
// Array("go1@go.com", "go2@go.com", "go3@go.com")

$m->setTo("visible@recipient.com"); // Everyone will the this e-mail. Doesn't need to be real.
$m->setBcc(Array("go1@go.com", "go2@go.com", "go3@go.com", "go4@o.com", "go5@go.com")); // Nobody will see this.

// Most servers have a destinations limit. It usually goes around 15 to 30. You'll have to figure it out by yourself.
$m->groupAmnt = 15; // Max destinations allowed by your servers
$m->delay     = 1;  // Seconds to wait before continue sending (can be zero, but personally I don't recommend)

// You can (should) log yours sending reports... You SHOULD read the logs.
var $logFolder = './log/';
var $logFile   = "send-".date('Y-m-d H-i-s').".dat";

// Send.
$m->send();

// Explanation: How does it work?
// Let's say you have 300 e-mails in your the "Bcc" list, and your groupAmnt is 30.
// The class will send 10 different e-mails, one for each "group" of targets.
// After sending 10 e-mails, it will sleep for 1 second so it won't overload server.
// Everyone will receive a copy of your e-mail, with the "visible@recipient.com" visible.

// Actually, to be honest, it will be 11 different e-mails. That's because the "visible@recipient.com"
// counts as an target in every loop... So, the "visible@recipient.com" message will receive 4 copies of the same message.

// Generally, when sending mass messages, you'll set the visible recipient to something like "undisclosed-recipients@yourdomain.com"
// You can (should) log 
