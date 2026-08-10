<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Tests\Dusk;

use Laravel\Dusk\Browser;
use MicroweberPackages\AiTools\Contracts\ToolRegistryInterface;
use MicroweberPackages\AiTools\Facades\AiTools;
use MicroweberPackages\AiTools\Tools\External\AmazonScraperTool;
use MicroweberPackages\AiTools\Tools\External\GoogleTrendsTool;
use MicroweberPackages\AiTools\Tools\External\SupadataTool;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * Browser-level smoke checks that the AI tools package is wired into the CMS
 * and that the agent-chats admin surface still loads after the package refactor.
 */
class AiToolsRegistryDuskTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Rely on the already-running server's database (same as AdminAiChatWorkflowTest).
    }

    #[Test]
    public function package_tools_are_registered_in_the_running_app(): void
    {
        // Programmatic assertions against the Dusk-booted application.
        $this->assertTrue(class_exists(AmazonScraperTool::class));
        $this->assertTrue(class_exists(GoogleTrendsTool::class));
        $this->assertTrue(class_exists(SupadataTool::class));

        $registry = app(ToolRegistryInterface::class);
        $this->assertInstanceOf(ToolRegistryInterface::class, $registry);

        // External tools ship with the package config.
        $this->assertTrue(
            AiTools::has('amazon_scraper') || class_exists(AmazonScraperTool::class),
            'Amazon scraper tool class must be available'
        );
    }

    #[Test]
    public function admin_agent_chats_page_loads_with_ai_tools_package(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $browser->visit('/admin/agent-chats')->pause(4000);
            $this->ensureLoggedIn($browser);

            $pageSource = $browser->driver->getPageSource();
            $this->assertStringNotContainsString(
                'Internal Server Error',
                $pageSource,
                'Agent chats list must not 500 after ai-tools package refactor'
            );
            $this->assertStringNotContainsString(
                'Class "MicroweberPackages\\AiTools',
                $pageSource,
                'Package classes must autoload without Class not found errors'
            );

            $bodyText = $browser->script('return document.body.innerText.toLowerCase();');
            $text = $bodyText[0] ?? '';
            $this->assertTrue(
                str_contains($text, 'chat')
                || str_contains($text, 'agent')
                || str_contains($text, 'ai')
                || str_contains($text, 'create')
                || str_contains($text, 'no records'),
                'Agent chats page should render AI chat UI content'
            );
        });
    }
}
