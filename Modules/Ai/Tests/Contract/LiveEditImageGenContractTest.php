<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Contract;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Source-contract guard for wiring AI image generation into the Live-Edit agent:
 *   - a Live-Edit generate_image tool that returns the PERMANENT site URL and the
 *     agent prompt that now says it CAN generate images (hero/section backgrounds);
 *   - the image drivers store generated images on the configured media disk
 *     (userfiles_disk — local "public" or S3), NOT a hardcoded public disk, so the
 *     returned URL is reachable wherever media lives.
 * Source-based (pre-boot) like the sibling LiveEditGuardsContractTest.
 */
final class LiveEditImageGenContractTest extends TestCase
{
    private function root(): string
    {
        return dirname(__DIR__, 4);
    }

    private function read(string $rel): string
    {
        $p = $this->root() . '/' . $rel;
        $this->assertFileExists($p, "$rel is missing.");

        return (string) file_get_contents($p);
    }

    #[Test]
    public function liveedit_generate_image_tool_returns_permanent_url(): void
    {
        $src = $this->read('Modules/Ai/Tools/LiveEdit/GenerateImageTool.php');
        $this->assertStringContainsString("'generate_image'", $src, 'The tool must be named generate_image.');
        $this->assertStringContainsString('AiImages::generateImage', $src, 'It must call the image service.');
        // Prefer the permanent, site-hosted URL over the expiring replicate output.
        $this->assertStringContainsString("\$result['url']", $src, 'It must return the permanent site URL, not the expiring provider URL.');
        $this->assertStringContainsString('isImageGenerationAvailable', $src, 'It must degrade gracefully when image gen is unconfigured.');
    }

    #[Test]
    public function agent_registers_generate_image_and_prompt_is_flipped(): void
    {
        $src = $this->read('Modules/Ai/Agents/LiveEditAgent.php');
        $this->assertStringContainsString('use Modules\Ai\Tools\LiveEdit\GenerateImageTool;', $src, 'GenerateImageTool must be imported.');
        $this->assertStringContainsString('new GenerateImageTool(', $src, 'GenerateImageTool must be registered in setupTools().');
        $this->assertStringContainsString('YOU CAN GENERATE IMAGES', $src, 'The prompt must tell the agent it can generate images.');
        $this->assertStringNotContainsString('You cannot generate images', $src, 'The stale "cannot generate images" rule must be gone.');
    }

    /**
     * @return array<string, array{0:string}>
     */
    public static function imageDriverProvider(): array
    {
        return [
            'replicate' => ['Modules/Ai/Services/Drivers/ReplicateAiDriver.php'],
            'fal' => ['Modules/Ai/Services/Drivers/FalAiDriver.php'],
        ];
    }

    #[Test]
    #[DataProvider('imageDriverProvider')]
    public function image_driver_uses_media_disk_not_hardcoded_public(string $rel): void
    {
        $src = $this->read($rel);
        // Strip comments so the assertion can't self-match prose that mentions the token.
        $code = (string) preg_replace('~//[^\n]*~', '', $src);
        $code = (string) preg_replace('~/\*[\s\S]*?\*/~', '', $code);

        $this->assertStringNotContainsString(
            "Storage::disk('public')",
            $code,
            "$rel must not hardcode the public disk for generated images — media may be on S3."
        );
        $this->assertMatchesRegularExpression(
            '~userfiles_disk\(\)|mediaDisk\(\)~',
            $code,
            "$rel must resolve the media disk via userfiles_disk()/mediaDisk()."
        );
    }

    #[Test]
    public function config_reads_standard_replicate_token(): void
    {
        $src = $this->read('Modules/Ai/config/config.php');
        $this->assertStringContainsString('REPLICATE_API_TOKEN', $src, 'Config must read the standard REPLICATE_API_TOKEN env name.');
    }
}
