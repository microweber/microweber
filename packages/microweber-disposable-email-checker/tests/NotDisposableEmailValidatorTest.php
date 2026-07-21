<?php

declare(strict_types=1);

namespace MicroweberPackages\DisposableEmailChecker\Tests;

use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;

class NotDisposableEmailValidatorTest extends TestCase
{
    #[Test]
    public function validation_rule_rejects_disposable_email(): void
    {
        $validator = Validator::make(
            ['email' => 'test@mailinator.com'],
            ['email' => 'required|email|not_disposable_email']
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    #[Test]
    public function validation_rule_allows_legitimate_email(): void
    {
        $validator = Validator::make(
            ['email' => 'user@gmail.com'],
            ['email' => 'required|email|not_disposable_email']
        );

        $this->assertFalse($validator->fails());
    }

    #[Test]
    public function validation_rule_allows_all_when_disabled(): void
    {
        config()->set('disposable-email-checker.enabled', false);

        $validator = Validator::make(
            ['email' => 'test@mailinator.com'],
            ['email' => 'required|email|not_disposable_email']
        );

        $this->assertFalse($validator->fails());
    }

    #[Test]
    public function validation_rule_passes_for_non_string_value(): void
    {
        $validator = Validator::make(
            ['email' => 123],
            ['email' => 'not_disposable_email']
        );

        $this->assertFalse($validator->fails());
    }
}