<?php
require_once "dSendMail2.inc.php";

// First of all: DISABLE TIME LIMIT and IGNORE USER ABORT! You really don't want to be interrupted in the middle of this.
set_time_limit(0);
ignore_user_abort(true);

// What is an EML? How to create it?
// - Open your Outlook Express
// - Write your message. Add your images, make your formatting, do what you want.
// - Now go to File > Save as. There you go, a brand new EML file.
// - Don't be afraid, it's nothing more than a TXT file... You can open it with notepad if you want.

// Loading the EML File.
$m = new dSendMail2;
$m->setEMLFile("my_email.eml");

// If your EML file is saved in your database, you may use:
// $m->importEML($eml_data)

// IMPORTANT:
// - You cannot access the 'user-friendly' contents, because there are none!
// - Class DOESN'T import subject, destination, and most of other headers

$m->setFrom("myself@mysite.com");
$m->setTo("target@domain.com");
$m->setSubject("Here goes my e-mail..");
$m->send();

// Ps: Maybe you want to use an EML file as template for your e-mails...
// In this case, you can put [MESSAGE] in the middle of your EML file and then:
// Not gauranteed.. We can never know what Microsoft will do with your message code.
// 
// $m->output = str_replace("[MESSAGE]", "Here goes your new message.", $m->output);
// $m->send();
// 
// Suggestion: If you want an e-mail template, make it as HTML.
