<?php
require_once "dSendMail2.inc.php";

// First of all: DISABLE TIME LIMIT and IGNORE USER ABORT! You really don't want to be interrupted in the middle of this.
set_time_limit(0);
ignore_user_abort(true);



// Ok, let's say all your e-mails have a default header and a footer.
// So, you may re-write your "mail()" function...
function myMail($to, $subject, $message){
	$m = new dSendMail2;
	$m->setFrom("myself@mysite.com");
	$m->setTo($to);
	$m->setSubject($subject);
	
	// If your message is not in HTML format, replace "\n" to "<br />"
	$message = nl2br($message);
	
	$templateDir  = dirname(__FILE__);
	$templateBody = file_get_contents("{$templateDir}/mail_template.html");
	$templateBody = str_replace("[MESSAGE]", $message, $templateBody);
	
	// Set body and import images:
	$m->importHTML($templateBody, $templateDir, true);
	
	return $m->send();
}

// Then...
myMail(
	"target@target.com",
	"Thanks for contacting us...",
	"We received your message and will reply you as soon as we can.\r\n".
	"\r\n".
	"Thanks and come back later!"
);
