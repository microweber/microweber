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
        $content = 'This is example message.';
        $from = 'peter@microweber.com';
        $fromName = 'Peter Microweber';

        $mail = new MailSender();
        $mail->setEmailTo($to);
        $mail->setEmailSubject($subject);
        $mail->setEmailReplyTo($replyTo);
        $mail->setEmailMessage($content);
        $mail->setEmailFrom($from);
        $mail->setEmailFromName($fromName);
        $mail->send();

        $checkEmailContent = file_get_contents(storage_path() . DIRECTORY_SEPARATOR  . 'mail_sender.txt');
        $checkEmailContent = json_decode($checkEmailContent, true);

        $this->assertSame($checkEmailContent['content'], $content);
        $this->assertSame($checkEmailContent['from_name'], $fromName);
        $this->assertSame($checkEmailContent['from'], $from);
        $this->assertSame($checkEmailContent['to'], $to);
        $this->assertSame($checkEmailContent['subject'], $subject);
    }

}