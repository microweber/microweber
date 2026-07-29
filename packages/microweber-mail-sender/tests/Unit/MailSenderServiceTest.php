<?php

declare(strict_types=1);

namespace MicroweberPackages\MailSender\Tests\Unit;

use Illuminate\Support\Facades\Mail;
use MicroweberPackages\MailSender\Services\MailSenderService;
use MicroweberPackages\MailSender\Tests\TestCase;

class MailSenderServiceTest extends TestCase
{
    protected MailSenderService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        $this->service = new MailSenderService();
        config([
            'mail-sender.enabled' => true,
            'mail-sender.from.address' => 'noreply@example.com',
            'mail-sender.from.name' => 'Example',
            'mail-sender.view' => 'mail-sender::emails.simple',
        ]);
    }

    public function test_can_instantiate(): void
    {
        $this->assertInstanceOf(MailSenderService::class, $this->service);
    }

    public function test_send_via_setters(): void
    {
        $to = 'user@example.com';
        $subject = 'Email subject';
        $content = 'This is example message.';
        $from = 'peter@example.com';
        $fromName = 'Peter';

        $this->service->setEmailTo($to);
        $this->service->setEmailSubject($subject);
        $this->service->setEmailReplyTo('reply@example.com');
        $this->service->setEmailMessage($content);
        $this->service->setEmailFrom($from);
        $this->service->setEmailFromName($fromName);

        // Mail::fake() intercepts Mail::send; we still record last_send.
        try {
            $this->service->send();
        } catch (\Throwable) {
            // Some mail fakes throw when view is missing in isolation; last_send still set.
        }

        $last = MailSenderService::$lastSend;
        $this->assertIsArray($last);
        $this->assertSame($content, $last['content']);
        $this->assertSame($fromName, $last['from_name']);
        $this->assertSame($from, $last['from']);
        $this->assertSame($to, $last['to']);
        $this->assertSame($subject, $last['subject']);
    }

    public function test_send_rejects_invalid_to(): void
    {
        $ok = $this->service->send('not-an-email', 'S', 'M');
        $this->assertFalse($ok);
    }

    public function test_send_with_hostname_prefix(): void
    {
        config(['mail-sender.hostname' => 'mysite.test']);
        try {
            $this->service->send('user@example.com', 'Hello', 'Body', true);
        } catch (\Throwable) {
            // ignore mail delivery
        }
        $last = MailSenderService::$lastSend;
        $this->assertIsArray($last);
        $this->assertStringContainsString('[mysite.test]', (string) $last['subject']);
    }

    public function test_statistics(): void
    {
        $stats = $this->service->getStatistics();
        $this->assertArrayHasKey('enabled', $stats);
        $this->assertArrayHasKey('transport', $stats);
        $this->assertArrayHasKey('version', $stats);
    }

    public function test_self_test(): void
    {
        $result = $this->service->selfTest();
        $this->assertArrayHasKey('ok', $result);
        $this->assertArrayHasKey('errors', $result);
        $this->assertTrue($result['ok']);
    }

    public function test_test_method_requires_valid_to(): void
    {
        $result = $this->service->test([]);
        $this->assertArrayHasKey('error', $result);
    }

    public function test_container_resolution(): void
    {
        $resolved = app(MailSenderService::class);
        $this->assertInstanceOf(MailSenderService::class, $resolved);
    }

    public function test_content_transformer(): void
    {
        $this->service->setContentTransformer(static fn (string $t): string => strtoupper($t));
        try {
            $this->service->execSend('a@b.com', 'S', 'hello', 'from@example.com', 'From');
        } catch (\Throwable) {
            // ignore
        }
        $last = MailSenderService::$lastSend;
        $this->assertIsArray($last);
        $this->assertSame('HELLO', $last['content']);
    }

    public function test_disabled_returns_false(): void
    {
        config(['mail-sender.enabled' => false]);
        $ok = $this->service->send('user@example.com', 'S', 'M');
        $this->assertFalse($ok);
    }
}
