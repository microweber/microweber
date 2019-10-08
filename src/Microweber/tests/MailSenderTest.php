<?php

namespace Microweber\tests;


use Microweber\Utils\MailSender;

/**
 * Run test
 * @author Bobi Slaveykvo Microweber
 * @command php phpunit.phar --filter MailSenderTest
 */
class MailSenderTest extends TestCase
{
    public function testSend()
    {

        $to = 'bobi@microweber.com';
        $subject = 'Email subject';
        $replyTo = 'Reply to';
        $message = 'This is example message.';
        $from = 'peter@microweber.com';
        $fromName = 'Peter Microweber';


        $mail = new MailSender();
        $mail->setEmailTo($to);
        $mail->setEmailSubject($subject);
        $mail->setEmailReplyTo($replyTo);
        $mail->setEmailMessage($message);
        $mail->setEmailFrom($from);
        $mail->setEmailFromName($fromName);
        $mail->send();

        $checkEmailContent = file_get_contents(storage_path() . DIRECTORY_SEPARATOR . 'mails' . DIRECTORY_SEPARATOR . 'mail_sender.txt');
        $checkEmailContent = json_decode($checkEmailContent, true);

        
        var_dump($checkEmailContent);
        die();

    }

}