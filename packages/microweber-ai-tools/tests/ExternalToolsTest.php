<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Tests;

use MicroweberPackages\AiTools\Tools\External\AmazonScraperTool;
use MicroweberPackages\AiTools\Tools\External\GoogleTrendsTool;
use MicroweberPackages\AiTools\Tools\External\SupadataTool;
use PHPUnit\Framework\Attributes\Test;

class ExternalToolsTest extends TestCase
{
    #[Test]
    public function amazon_scraper_metadata_and_validation(): void
    {
        $tool = new AmazonScraperTool();

        $this->assertSame('amazon_scraper', $tool->getName());
        $this->assertSame('content', $tool->getDomain());
        $this->assertNotEmpty($tool->getProperties());

        $result = $tool(['action' => 'unknown_action']);
        $this->assertStringContainsString('Unknown action', $result);

        $searchMissing = $tool(['action' => 'search']);
        $this->assertStringContainsString('Query parameter is required', $searchMissing);
    }

    #[Test]
    public function google_trends_metadata(): void
    {
        $tool = new GoogleTrendsTool();

        $this->assertSame('google_trends', $tool->getName());
        $this->assertSame('trends', $tool->getDomain());
        $this->assertNotEmpty($tool->getProperties());
    }

    #[Test]
    public function supadata_requires_api_key_and_query(): void
    {
        config(['ai-tools.services.supadata.api_key' => null, 'modules.ai.drivers.supadata.api_key' => null]);

        $tool = new SupadataTool();
        $this->assertSame('supadata_search', $tool->getName());

        $noKey = $tool(['query' => 'test']);
        $this->assertStringContainsString('API key not configured', $noKey);

        config(['ai-tools.services.supadata.api_key' => 'test-key']);
        $empty = $tool(['query' => '']);
        $this->assertStringContainsString('Query parameter is required', $empty);

        $ok = $tool(['query' => 'hello']);
        $this->assertStringContainsString('hello', $ok);
        $this->assertStringContainsString('ready', $ok);
    }

    #[Test]
    public function amazon_marketplaces_action_returns_html(): void
    {
        $tool = new AmazonScraperTool();
        $html = $tool(['action' => 'get_marketplaces']);

        $this->assertStringContainsString('Amazon', $html);
        $this->assertStringContainsString('US', $html);
    }
}
