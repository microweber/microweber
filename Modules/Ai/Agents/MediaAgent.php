<?php

declare(strict_types=1);

namespace Modules\Ai\Agents;

use Illuminate\Support\Facades\Config;
use NeuronAI\Agent\SystemPrompt;
use NeuronAI\Workflow\WorkflowState;

class MediaAgent extends BaseAgent
{
    protected string $domain = 'media';

    public function __construct(
        ?string $providerName = null,
        ?string $model = null,
        protected array $dependencies = []
    ) {
        parent::__construct($providerName, $model, $dependencies);
    }

    public function instructions(): string
    {
        return (string)new SystemPrompt(
            background: [
                'You are an AI Agent specialized in Media Management for the Microweber CMS.',
                'You can help with image optimization, file organization, media library management.',
                'You assist with media-related tasks including file uploads, image processing, and gallery management.',
                'You can transcribe YouTube videos to create video summaries and extract key information for media content.',
            ],
            steps: [
                'When asked about media management, provide guidance on best practices.',
                'Help with image optimization and file organization strategies.',
                'Suggest proper file naming conventions and folder structures.',
                'Provide advice on image formats, sizes, and compression.',
                'When provided with YouTube URLs, transcribe videos and create summaries for media content creation.',
            ],
            output: [
                'Always respond with well-formatted HTML content.',
                'Provide actionable media management recommendations.',
                'Include examples and best practices for media handling.',
                'Use clear formatting to make information easy to understand.',
            ],
        );
    }

    protected function setupTools(): void
    {
        $this->loadToolsFromRegistry([
            'media_search',
            'media_lookup',
            'media_asset_detail',
            'media_storage_health',
            'rag_search',
        ]);

        if (Config::get('modules.ai.drivers.supadata.enabled') && Config::get('modules.ai.drivers.supadata.api_key')) {
            $this->loadToolsFromRegistry(['get_youtube_transcription']);
        }
    }
}
