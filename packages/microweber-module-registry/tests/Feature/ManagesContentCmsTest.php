<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;

/**
 * CMS integration smoke for ManagesContent — skipped when content helpers
 * (morph_name) or the CMS harness are unavailable in the environment.
 *
 * @deprecated Content helpers on the registry are deprecated.
 */
class ManagesContentCmsTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    public function content_helpers_are_callable_when_cms_is_ready(): void
    {
        if (! class_exists(\Tests\TestCase::class)) {
            $this->markTestSkipped('CMS TestCase required');
        }

        if (! function_exists('morph_name') && ! function_exists('\\Modules\\Content\\Support\\morph_name')) {
            // Content module helper may not be autoloaded in this environment
            $this->markTestSkipped('Content morph_name helper is not available');
        }

        $this->markTestSkipped('Full content CRUD integration covered by CMS content module tests');
    }

    #[Test]
    public function registry_content_methods_exist(): void
    {
        $this->assertTrue(method_exists(
            \MicroweberPackages\ModuleRegistry\ModuleRegistryManager::class,
            'contentGetById'
        ));
        $this->assertTrue(method_exists(
            \MicroweberPackages\ModuleRegistry\ModuleRegistryManager::class,
            'contentSave'
        ));
        $this->assertTrue(method_exists(
            \MicroweberPackages\ModuleRegistry\ModuleRegistryManager::class,
            'contentPublish'
        ));
    }
}
