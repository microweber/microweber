<?php

declare(strict_types=1);

namespace MicroweberPackages\MailSender\Tests\Unit;

use MicroweberPackages\MailSender\Services\MailConfigApplier;
use MicroweberPackages\MailSender\Tests\TestCase;

class MailConfigApplierTest extends TestCase
{
    public function test_applies_smtp_config(): void
    {
        $applier = new MailConfigApplier();
        $applier->apply([
            'transport' => 'smtp',
            'from' => ['address' => 'a@b.com', 'name' => 'A'],
            'smtp' => [
                'host' => 'smtp.test',
                'port' => 465,
                'username' => 'u',
                'password' => 'p',
                'encryption' => 'ssl',
            ],
        ]);

        $this->assertSame('a@b.com', config('mail.from.address'));
        $this->assertSame('A', config('mail.from.name'));
        $this->assertSame('smtp.test', config('mail.host'));
        $this->assertSame(465, config('mail.port'));
        $this->assertSame('ssl', config('mail.encryption'));
        $this->assertSame('u', config('mail.username'));
        $this->assertSame('smtp', config('mail.default'));
    }

    public function test_gmail_preset(): void
    {
        $applier = new MailConfigApplier();
        $applier->apply([
            'transport' => 'gmail',
            'from' => ['address' => 'g@gmail.com', 'name' => 'G'],
            'smtp' => ['host' => 'ignored', 'port' => 25, 'username' => null, 'password' => null, 'encryption' => null],
        ]);

        $this->assertSame('smtp.gmail.com', config('mail.host'));
        $this->assertSame(587, config('mail.port'));
        $this->assertSame('tls', config('mail.encryption'));
    }

    public function test_config_transport_is_noop(): void
    {
        config(['mail.host' => 'keep.me']);
        $applier = new MailConfigApplier();
        $applier->apply([
            'transport' => 'config',
            'from' => ['address' => 'x@y.com', 'name' => 'X'],
            'smtp' => [],
        ]);
        $this->assertSame('keep.me', config('mail.host'));
    }

    public function test_php_transport_maps_to_sendmail(): void
    {
        $applier = new MailConfigApplier();
        $applier->apply([
            'transport' => 'php',
            'from' => ['address' => 'a@b.com', 'name' => 'A'],
            'smtp' => [],
        ]);
        $this->assertSame('sendmail', config('mail.default'));
    }
}
