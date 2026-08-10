<?php

declare(strict_types=1);

namespace Tests\Feature;

use MicroweberPackages\AiTools\Contracts\ToolRegistryInterface;
use MicroweberPackages\AiTools\Facades\AiTools;
use MicroweberPackages\AiTools\Providers\AiToolsServiceProvider;
use MicroweberPackages\AiTools\Tools\External\AmazonScraperTool;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * CMS-level integration tests for the extracted microweber-ai-tools package.
 */
class AiToolsPackageIntegrationTest extends TestCase
{
    #[Test]
    public function package_service_provider_is_loaded_by_the_cms(): void
    {
        $this->assertNotNull(
            $this->app->getProvider(AiToolsServiceProvider::class),
            'AiToolsServiceProvider must be registered via CoreServiceProvider'
        );
        $this->assertTrue($this->app->bound(ToolRegistryInterface::class));
    }

    #[Test]
    public function external_package_tools_are_in_the_registry(): void
    {
        $this->assertTrue(AiTools::has('amazon_scraper'));
        $this->assertTrue(AiTools::has('google_trends'));
        $this->assertTrue(AiTools::has('supadata_search'));

        $tool = AiTools::make('amazon_scraper');
        $this->assertInstanceOf(AmazonScraperTool::class, $tool);
        $this->assertSame('amazon_scraper', $tool->getName());
    }

    #[Test]
    public function module_tools_register_into_the_shared_registry(): void
    {
        // Content / settings tools should be registered by their modules.
        $expected = [
            'create_content',
            'content_search',
            'settings_read',
            'product_search',
            'media_search',
        ];

        $missing = [];
        foreach ($expected as $name) {
            if (!AiTools::has($name)) {
                $missing[] = $name;
            }
        }

        $this->assertSame(
            [],
            $missing,
            'Module tools must register into AiTools registry. Missing: ' . implode(', ', $missing)
            . '. Registered: ' . implode(', ', AiTools::names())
        );
    }

    #[Test]
    public function general_agent_loads_tools_from_registry(): void
    {
        $agentFactory = app(\Modules\Ai\Services\AgentFactory::class);
        $ragService = app(\Modules\Ai\Services\RagSearchService::class);
        $agent = new \Modules\Ai\Agents\GeneralAgent($agentFactory, $ragService);
        $tools = $agent->getTools();
        $names = array_map(static function ($tool) {
            return method_exists($tool, 'getName') ? $tool->getName() : (string) $tool->getName();
        }, $tools);

        $this->assertContains('content_search', $names);
        $this->assertContains('settings_read', $names);
        $this->assertNotEmpty($names);
    }

    #[Test]
    public function old_src_package_path_is_gone(): void
    {
        $this->assertDirectoryDoesNotExist(base_path('src/MicroweberPackages/AiTools'));
        $this->assertDirectoryExists(base_path('packages/microweber-ai-tools/src'));
        $this->assertFileDoesNotExist(base_path('Modules/Ai/Tools/BaseTool.php'));
        $this->assertFileDoesNotExist(base_path('Modules/Ai/Tools/CreateContentTool.php'));
        $this->assertFileExists(base_path('Modules/Content/Tools/CreateContentTool.php'));
    }
}
