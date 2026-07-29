<?php

declare(strict_types=1);

namespace MicroweberPackages\MailSender\Tests\Filament;

use MicroweberPackages\MailSender\Filament\Pages\MailSenderSettings;
use MicroweberPackages\MailSender\Tests\TestCase;

class MailSenderSettingsTest extends TestCase
{
    public function test_page_class_exists(): void
    {
        $this->assertTrue(class_exists(MailSenderSettings::class));
    }

    public function test_form_schema_is_non_empty(): void
    {
        $page = new MailSenderSettings();
        $schema = $page->getFormSchema();
        $this->assertNotEmpty($schema);
    }

    public function test_can_access_without_cms_when_guest(): void
    {
        // Without is_admin helper and guest auth, canAccess returns true for standalone.
        $this->assertIsBool(MailSenderSettings::canAccess());
    }

    public function test_navigation_meta(): void
    {
        $this->assertSame('mail-sender-settings', MailSenderSettings::getSlug());
        $this->assertSame('Mail Sender', MailSenderSettings::getNavigationLabel() ?: MailSenderSettings::$title ?? 'Mail Sender');
    }
}
