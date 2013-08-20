<?php
require_once "dSendMail2.inc.php";

// First of all: DISABLE TIME LIMIT and IGNORE USER ABORT! You really don't want to be interrupted in the middle of this.
set_time_limit(0);
ignore_user_abort(true);

// Basics - Setting the message...
$m = new dSendMail2;
$m->setSubject("Attachment Test");
$m->setFrom("nobody@nowhere.com");    // Visible "From:" header. Recipient will see this.
$m->setTo  ("nobody@nowhere.com");    // Visible and functional recipient(s).
$m->setMessage(
	"Report is attached in this message.<br />".
	"Note that <b>HTML</b> mode is enabled by default.<br />".
	"To embed an image, just call it here..<br />".
	"<img src='my_image.jpg' /><br />".
	"Image is shown above here.<br />"
);

// Important:
// - For embedded images to work, message MUST be set before the attachments.

// Attach the file.
$m->autoAttachFile("report.xls", file_get_contents("myreport.xls"));

// Attach embedded image.
$m->autoAttachFile("my_image.jpg", file_get_contents("my_image.jpg"));

// Send message.
$m->send();


// Explanation: How does it work?
// - When you attach a file, class will see if you're using it in your HTML message.
// - If YES, it will embedded image. Otherwise, will only attach.


