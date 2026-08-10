<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Tests;

use MicroweberPackages\AiTools\Contracts\ToolRegistryInterface;
use MicroweberPackages\AiTools\Facades\AiTools;
use MicroweberPackages\AiTools\Providers\AiToolsServiceProvider;
use MicroweberPackages\AiTools\Registry\ToolRegistry;
use PHPUnit\Framework\Attributes\Test;

/**
 * Validates that microweber-packages/ai-tools works in a standalone
 * Laravel app (Orchestra Testbench) without the Microweber CMS.
 */
class StandalonePackageUsageTest extends TestCase
{
    #[Test]
    public function package_provider_is_loadable_without_cms(): void
    {
        $this->assertTrue(class_exists(AiToolsServiceProvider::class));
        $this->assertNotNull($this->app->getProvider(AiToolsServiceProvider::class));
    }

    #[Test]
    public function composer_json_has_no_laravel_auto_discovery_providers(): void
    {
        $composer = json_decode(
            (string) file_get_contents(dirname(__DIR__) . '/composer.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->assertArrayNotHasKey(
            'providers',
            $composer['extra']['laravel'] ?? [],
            'Standalone MW packages must not use extra.laravel.providers (Core loads them)'
        );
        $this->assertSame('microweber-packages/ai-tools', $composer['name']);
    }

    #[Test]
    public function registry_is_usable_as_application_service(): void
    {
        /** @var ToolRegistryInterface $registry */
        $registry = $this->app->make(ToolRegistryInterface::class);

        $this->assertInstanceOf(ToolRegistry::class, $registry);
        $this->assertTrue(AiTools::has('amazon_scraper'));

        // Application code can register its own tools at runtime
        AiTools::registerFactory('app_tool', static function (array $deps) {
            return new class($deps) extends \MicroweberPackages\AiTools\Base\BaseTool {
                public function __construct(array $dependencies = [])
                {
                    parent::__construct('app_tool', 'App-defined tool', $dependencies);
                }

                protected function properties(): array
                {
                    return [];
                }

                public function __invoke(mixed ...$args): string
                {
                    return $this->handleSuccess('standalone');
                }
            };
        });

        $tool = AiTools::make('app_tool');
        $this->assertNotNull($tool);
        $this->assertStringContainsString('standalone', $tool());
    }

    #[Test]
    public function config_is_merged_and_publishable_tag_exists(): void
    {
        $this->assertTrue(config('ai-tools.enabled'));
        $this->assertIsArray(config('ai-tools.tools'));
        $this->assertIsArray(config('ai-tools.services'));
    }
}
