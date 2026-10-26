<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Ai\Facades\AiImages;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit tool: generate an image with AI and return its PERMANENT site URL.
 *
 * Wraps the configured image provider (AiImages → Replicate by default). The
 * driver downloads the result into the site media (storage/app/public/media/
 * replicate) and returns a permanent public URL — the raw replicate.delivery URL
 * would expire, so the agent must use the permanent one. The agent then APPLIES
 * the image: apply_css `background-image: url(...)` for a hero/section background,
 * or set_image(selector, url) for an <img>. Only works when an image provider is
 * enabled with an API key (e.g. REPLICATE_ENABLED + REPLICATE_API_TOKEN).
 */
class GenerateImageTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'generate_image',
            'Generate an image with AI (a hero/section background, an illustration, a '
            . 'scene or product visual) and get back a PERMANENT image URL hosted on '
            . 'this site. Use a vivid, specific prompt (subject, style, composition, '
            . 'colours, mood). Then APPLY it: for a hero/section background call '
            . 'apply_css with background-image: url(URL); background-size: cover; for a '
            . 'content picture call set_image(selector, URL). Prefer ONE strong wide '
            . 'image for a hero over many small ones. Only works when an image provider '
            . 'is configured.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'prompt',
                type: PropertyType::STRING,
                description: 'A vivid, specific description: subject, style (e.g. '
                    . 'cinematic photo, flat illustration, oil painting), composition, '
                    . 'colour palette and mood. e.g. "cinematic wide photo of a '
                    . 'lighthouse beam over a crowd on a dark shore at dusk, warm gold '
                    . 'light, deep blue sea, dramatic".',
                required: true,
            ),
            new ToolProperty(
                name: 'aspect',
                type: PropertyType::STRING,
                description: 'Aspect ratio: "16:9" (wide hero, the default), "1:1" '
                    . '(square), "4:3", "3:4" or "9:16" (portrait).',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $prompt = trim((string) ($args['prompt'] ?? ''));
        if ($prompt === '') {
            return $this->handleError('An image prompt is required.');
        }
        $aspect = trim((string) ($args['aspect'] ?? '16:9'));
        if ($aspect === '') {
            $aspect = '16:9';
        }

        try {
            $svc = app('ai.images');
            if (method_exists($svc, 'isImageGenerationAvailable') && !$svc->isImageGenerationAvailable()) {
                return $this->handleError(
                    'Image generation is not configured. Enable an image provider (e.g. '
                    . 'Replicate: REPLICATE_ENABLED=true + REPLICATE_API_TOKEN) in the AI '
                    . 'settings. For now, build a styled placeholder box with add_section '
                    . '+ apply_css instead.'
                );
            }

            $result = AiImages::generateImage(
                [['role' => 'user', 'content' => $prompt]],
                ['number_of_images' => 1, 'aspect_ratio' => $aspect, 'response_format' => 'url']
            );

            $url = '';
            if (is_array($result)) {
                $url = (string) ($result['url'] ?? ($result['urls'][0] ?? ($result['output'] ?? '')));
            } elseif (is_string($result)) {
                $url = $result;
            }
            if ($url === '') {
                return $this->handleError('Image generation returned no usable URL.');
            }
        } catch (\Throwable $e) {
            return $this->handleError('Could not generate the image: ' . $e->getMessage());
        }

        return "Generated image (saved to the site media; permanent URL):\n" . $url
            . "\nNow APPLY it: for a hero/section background call apply_css with "
            . "background-image: url('" . $url . "'); background-size: cover; background-position: center; "
            . "on that section (and set a readable text colour over it); for a content "
            . "<img> call set_image(selector, '" . $url . "').";
    }
}
