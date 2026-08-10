<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;

use Modules\Ai\Facades\AiImages;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class GenerateImageTool extends BaseTool
{
    protected string $domain = 'content';
    protected array $requiredPermissions = ['generate images'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'generate_image',
            'Generate AI images based on text prompts using various AI image generation models like DALL-E, Stable Diffusion, or other supported image generation services.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'prompt',
                type: PropertyType::STRING,
                description: 'Detailed text description of the image you want to generate. Be specific about style, composition, colors, mood, and any particular elements you want included.',
                required: true,
            ),
            new ToolProperty(
                name: 'style',
                type: PropertyType::STRING,
                description: 'Image style/type. Options: "photorealistic", "artistic", "cartoon", "abstract", "digital_art", "oil_painting", "watercolor", "sketch", "3d_render". Default is "photorealistic".',
                required: false,
            ),
            new ToolProperty(
                name: 'size',
                type: PropertyType::STRING,
                description: 'Image dimensions. Options: "1024x1024" (square), "1024x768" (landscape), "768x1024" (portrait), "1280x720" (wide). Default is "1024x1024".',
                required: false,
            ),
            new ToolProperty(
                name: 'quality',
                type: PropertyType::STRING,
                description: 'Image quality level. Options: "standard", "high", "hd". Default is "standard".',
                required: false,
            ),
            new ToolProperty(
                name: 'number_of_images',
                type: PropertyType::INTEGER,
                description: 'Number of images to generate (1-4). Default is 1.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        // Extract parameters from args array using keys
        $prompt = $args['prompt'] ?? '';
        $style = $args['style'] ?? 'photorealistic';
        $size = $args['size'] ?? '1024x1024';
        $quality = $args['quality'] ?? 'standard';
        $number_of_images = $args['number_of_images'] ?? 1;

        if (!$this->authorize()) {
            return $this->handleError('You do not have permission to generate images.');
        }

        // Validate inputs
        if (empty($prompt)) {
            return $this->handleError('Image prompt is required. Please provide a detailed description of the image you want to generate.');
        }

        // Validate number of images
        $number_of_images = max(1, min(4, (int)$number_of_images));

        // Validate size
        $validSizes = ['1024x1024', '1024x768', '768x1024', '1280x720', '512x512'];
        if (!in_array($size, $validSizes)) {
            $size = '1024x1024';
        }

        // Validate quality
        $validQualities = ['standard', 'high', 'hd'];
        if (!in_array($quality, $validQualities)) {
            $quality = 'standard';
        }

        // Validate style
        $validStyles = ['photorealistic', 'artistic', 'cartoon', 'abstract', 'digital_art', 'oil_painting', 'watercolor', 'sketch', '3d_render'];
        if (!in_array($style, $validStyles)) {
            $style = 'photorealistic';
        }

        try {
            // Enhance prompt with style if specified
            $enhancedPrompt = $this->enhancePromptWithStyle($prompt, $style);

            // Prepare messages array for the AI service
            $messages = [
                [
                    'role' => 'user',
                    'content' => $enhancedPrompt
                ]
            ];

            // Prepare options for image generation
            $options = [
                'size' => $size,
                'quality' => $quality,
                'number_of_images' => $number_of_images,
                'style' => $style,
                'response_format' => 'url' // Request URLs instead of base64 for easier handling
            ];

            // Generate the image using the AiImages facade
            $result = AiImages::generateImage($messages, $options);

            return $this->formatImageGenerationResult($result, $prompt, $style, $size, $quality, $number_of_images);

        } catch (\Exception $e) {
            return $this->handleError('Error generating image: ' . $e->getMessage());
        }
    }

    protected function enhancePromptWithStyle(string $prompt, string $style): string
    {
        $styleEnhancements = [
            'photorealistic' => 'highly detailed photorealistic',
            'artistic' => 'artistic and creative',
            'cartoon' => 'cartoon style illustration',
            'abstract' => 'abstract artistic interpretation',
            'digital_art' => 'digital art style',
            'oil_painting' => 'oil painting style',
            'watercolor' => 'watercolor painting style',
            'sketch' => 'pencil sketch style',
            '3d_render' => '3D rendered'
        ];

        $enhancement = $styleEnhancements[$style] ?? '';
        
        if ($enhancement && !str_contains(strtolower($prompt), strtolower($enhancement))) {
            return $enhancement . ' ' . $prompt;
        }

        return $prompt;
    }

    protected function formatImageGenerationResult($result, string $prompt, string $style, string $size, string $quality, int $count): string
    {
        $header = "
        <div class='image-generation-header mb-3'>
            <h4><i class='fas fa-image text-primary me-2'></i>AI Image Generation Results</h4>
            <div class='generation-details p-3 bg-light rounded mb-3'>
                <p class='mb-2'><strong>Prompt:</strong> " . htmlspecialchars($prompt) . "</p>
                <div class='row'>
                    <div class='col-md-3'><strong>Style:</strong> " . ucfirst(str_replace('_', ' ', $style)) . "</div>
                    <div class='col-md-3'><strong>Size:</strong> {$size}</div>
                    <div class='col-md-3'><strong>Quality:</strong> " . ucfirst($quality) . "</div>
                    <div class='col-md-3'><strong>Count:</strong> {$count}</div>
                </div>
            </div>
        </div>";

        // Handle different result formats
        if (is_string($result)) {
            // Single image URL
            return $header . $this->formatSingleImage($result, $prompt);
        } elseif (is_array($result)) {
            // Multiple images or structured response
            return $header . $this->formatMultipleImages($result, $prompt);
        }

        return $header . '<div class="alert alert-success">Images generated successfully! Check your media library or the response from the AI service.</div>';
    }

    protected function formatSingleImage(string $imageUrl, string $prompt): string
    {
        return "
        <div class='row'>
            <div class='col-md-6 col-lg-4 mb-3'>
                <div class='card h-100'>
                    <img src='{$imageUrl}' class='card-img-top' style='height: 300px; object-fit: cover;' alt='" . htmlspecialchars($prompt) . "'>
                    <div class='card-body'>
                        <h6 class='card-title'>Generated Image</h6>
                        <p class='card-text small'>" . \Str::limit($prompt, 80) . "</p>
                        <a href='{$imageUrl}' target='_blank' class='btn btn-sm btn-primary'>View Full Size</a>
                        <button class='btn btn-sm btn-secondary ms-2' onclick='navigator.clipboard.writeText(\"{$imageUrl}\")'>Copy URL</button>
                    </div>
                </div>
            </div>
        </div>";
    }

    protected function formatMultipleImages(array $result, string $prompt): string
    {
        $html = "<div class='row'>";

        // Handle different array structures
        $images = [];
        
        if (isset($result['data']) && is_array($result['data'])) {
            // OpenAI-style response
            foreach ($result['data'] as $image) {
                if (isset($image['url'])) {
                    $images[] = $image['url'];
                }
            }
        } elseif (isset($result['images']) && is_array($result['images'])) {
            // Other service response format
            $images = $result['images'];
        } elseif (is_array($result) && !empty($result)) {
            // Direct array of URLs
            $images = array_filter($result, 'is_string');
        }

        foreach ($images as $index => $imageUrl) {
            $imageNumber = $index + 1;
            $html .= "
            <div class='col-md-6 col-lg-4 mb-3'>
                <div class='card h-100'>
                    <img src='{$imageUrl}' class='card-img-top' style='height: 300px; object-fit: cover;' alt='" . htmlspecialchars($prompt) . "'>
                    <div class='card-body'>
                        <h6 class='card-title'>Generated Image #{$imageNumber}</h6>
                        <p class='card-text small'>" . \Str::limit($prompt, 80) . "</p>
                        <a href='{$imageUrl}' target='_blank' class='btn btn-sm btn-primary'>View Full Size</a>
                        <button class='btn btn-sm btn-secondary ms-2' onclick='navigator.clipboard.writeText(\"{$imageUrl}\")'>Copy URL</button>
                    </div>
                </div>
            </div>";
        }

        $html .= "</div>";

        if (empty($images)) {
            return '<div class="alert alert-warning">Images were generated but could not be displayed. Please check your AI service configuration.</div>';
        }

        return $html;
    }
}
