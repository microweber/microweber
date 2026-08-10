<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Tests;

use MicroweberPackages\AiTools\Base\BaseTool;
use MicroweberPackages\AiTools\Contracts\ToolInterface;
use MicroweberPackages\AiTools\Contracts\ToolRegistryInterface;
use MicroweberPackages\AiTools\Facades\AiTools;
use MicroweberPackages\AiTools\Registry\ToolRegistry;
use MicroweberPackages\AiTools\Tools\External\AmazonScraperTool;
use MicroweberPackages\AiTools\Tools\External\GoogleTrendsTool;
use MicroweberPackages\AiTools\Tools\External\SupadataTool;
use PHPUnit\Framework\Attributes\Test;

class ToolRegistryTest extends TestCase
{
    #[Test]
    public function service_provider_binds_registry_as_singleton(): void
    {
        $a = $this->app->make(ToolRegistryInterface::class);
        $b = $this->app->make(ToolRegistryInterface::class);
        $c = $this->app->make('aitools.registry');

        $this->assertInstanceOf(ToolRegistry::class, $a);
        $this->assertSame($a, $b);
        $this->assertSame($a, $c);
    }

    #[Test]
    public function config_tools_are_auto_registered_on_boot(): void
    {
        $registry = $this->app->make(ToolRegistryInterface::class);

        $this->assertTrue($registry->has('amazon_scraper'));
        $this->assertTrue($registry->has('google_trends'));
        $this->assertTrue($registry->has('supadata_search'));
        $this->assertGreaterThanOrEqual(3, $registry->count());
    }

    #[Test]
    public function register_and_get_round_trip(): void
    {
        $registry = $this->app->make(ToolRegistryInterface::class);
        $registry->clear();

        $registry->register(SupadataTool::class);

        $tool = $registry->get('supadata_search');
        $this->assertInstanceOf(SupadataTool::class, $tool);
        $this->assertSame('supadata_search', $tool->getName());
        $this->assertSame(SupadataTool::class, $registry->getClass('supadata_search'));
    }

    #[Test]
    public function make_creates_fresh_instances(): void
    {
        $registry = $this->app->make(ToolRegistryInterface::class);
        $registry->clear();
        $registry->register(AmazonScraperTool::class);

        $a = $registry->make('amazon_scraper');
        $b = $registry->make('amazon_scraper');

        $this->assertInstanceOf(AmazonScraperTool::class, $a);
        $this->assertInstanceOf(AmazonScraperTool::class, $b);
        $this->assertNotSame($a, $b);
    }

    #[Test]
    public function aliases_resolve_to_canonical_tool(): void
    {
        $registry = $this->app->make(ToolRegistryInterface::class);
        $registry->clear();
        $registry->register(GoogleTrendsTool::class);
        $registry->registerAlias('trends', 'google_trends');

        $this->assertTrue($registry->has('trends'));
        $this->assertInstanceOf(GoogleTrendsTool::class, $registry->get('trends'));
        $this->assertSame('google_trends', $registry->get('trends')?->getName());
    }

    #[Test]
    public function get_by_domain_filters_tools(): void
    {
        $registry = $this->app->make(ToolRegistryInterface::class);
        $registry->clear();
        $registry->register(AmazonScraperTool::class);
        $registry->register(GoogleTrendsTool::class);

        $content = $registry->getByDomain('content');
        $trends = $registry->getByDomain('trends');

        $this->assertArrayHasKey('amazon_scraper', $content);
        $this->assertArrayHasKey('google_trends', $trends);
        $this->assertArrayNotHasKey('google_trends', $content);
    }

    #[Test]
    public function register_instance_and_factory(): void
    {
        $registry = $this->app->make(ToolRegistryInterface::class);
        $registry->clear();

        $custom = new class extends BaseTool {
            public function __construct()
            {
                parent::__construct('custom_echo', 'Echoes input', []);
                $this->domain = 'custom';
            }

            protected function properties(): array
            {
                return [];
            }

            public function __invoke(mixed ...$args): string
            {
                $params = is_array($args[0] ?? null) ? $args[0] : $args;

                return $this->handleSuccess((string) ($params['text'] ?? ''));
            }
        };

        $registry->registerInstance($custom);
        $this->assertTrue($registry->has('custom_echo'));
        $this->assertStringContainsString('hello', (string) $registry->get('custom_echo')?->__invoke(['text' => 'hello']));

        $registry->registerFactory('factory_tool', static function (array $deps): ToolInterface {
            return new class($deps) extends BaseTool {
                public function __construct(array $dependencies = [])
                {
                    parent::__construct('factory_tool', 'Built by factory', $dependencies);
                    $this->domain = 'custom';
                }

                protected function properties(): array
                {
                    return [];
                }

                public function __invoke(mixed ...$args): string
                {
                    return $this->handleSuccess('ok');
                }
            };
        });

        $this->assertTrue($registry->has('factory_tool'));
        $made = $registry->make('factory_tool', ['x' => 1]);
        $this->assertNotNull($made);
        $this->assertSame('factory_tool', $made->getName());
    }

    #[Test]
    public function facade_proxies_registry(): void
    {
        AiTools::clear();
        AiTools::register(SupadataTool::class);

        $this->assertTrue(AiTools::has('supadata_search'));
        $this->assertContains('supadata_search', AiTools::names());
        $this->assertSame(1, AiTools::count());
    }

    #[Test]
    public function unregister_and_clear(): void
    {
        $registry = $this->app->make(ToolRegistryInterface::class);
        $registry->clear();
        $registry->register(SupadataTool::class);
        $registry->registerAlias('sd', 'supadata_search');

        $registry->unregister('supadata_search');
        $this->assertFalse($registry->has('supadata_search'));
        $this->assertFalse($registry->has('sd'));

        $registry->register(AmazonScraperTool::class);
        $registry->clear();
        $this->assertSame(0, $registry->count());
        $this->assertSame([], $registry->names());
    }

    #[Test]
    public function invalid_class_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->app->make(ToolRegistryInterface::class)->register('\\Does\\Not\\Exist');
    }

    #[Test]
    public function non_tool_interface_class_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->app->make(ToolRegistryInterface::class)->register(\stdClass::class);
    }
}
