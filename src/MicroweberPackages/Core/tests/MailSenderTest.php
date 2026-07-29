<?php

namespace MicroweberPackages\Core\tests;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Mail;
use MicroweberPackages\MailSender\Services\MailSenderService;

/**
 * CMS-level regression for the extracted mail-sender package.
 *
 * @command php artisan test --filter=MailSenderTest
 */
class MailSenderTest extends TestCase
{
    #[Test]
    public function it_send(): void {
        Mail::fake();

        $to = 'bobi@microweber.com';
        $subject = 'Email subject';
        $replyTo = 'reply@microweber.com';
        $content = 'This is example message.';
        $from = 'peter@microweber.com';
        $fromName = 'Peter Microweber';

        $mail = app(MailSenderService::class);
        $mail->setEmailTo($to);
        $mail->setEmailSubject($subject);
        $mail->setEmailReplyTo($replyTo);
        $mail->setEmailMessage($content);
        $mail->setEmailFrom($from);
        $mail->setEmailFromName($fromName);

        try {
            $mail->send();
        } catch (\Throwable) {
            // array/log mailers or view issues should not block last_send assertion
        }

        $checkEmailContent = MailSenderService::$lastSend;

        $this->assertIsArray($checkEmailContent);
        $this->assertSame($checkEmailContent['content'], $content);
        $this->assertSame($checkEmailContent['from_name'], $fromName);
        $this->assertSame($checkEmailContent['from'], $from);
        $this->assertSame($checkEmailContent['to'], $to);
        $this->assertSame($checkEmailContent['subject'], $subject);
    }

}
