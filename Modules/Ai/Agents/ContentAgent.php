<?php

declare(strict_types=1);

namespace Modules\Ai\Agents;

use Illuminate\Support\Facades\Config;
use Modules\Ai\Tools\MediaSearchTool;
use NeuronAI\SystemPrompt;
use NeuronAI\Tools\Toolkits\Tavily\TavilySearchTool;
use NeuronAI\Workflow\WorkflowState;

class ContentAgent extends BaseAgent
{
    protected string $domain = 'content';

    public function __construct(
        ?string         $providerName = null,
        ?string         $model = null,
        protected array $dependencies = []
    )
    {
        parent::__construct($providerName, $model, $dependencies);
    }

    public function instructions(): string
    {
        return (string)new SystemPrompt(
            background: [
                'You are an AI Agent specialized in Content Management for the Microweber CMS.',
                'You can help with content creation, editing, SEO optimization, and content analysis.',
                'You assist with pages, posts, blog articles, and general content management tasks.',
                'You have access to Google Trends data to help create trending, relevant content.',
                'You can research trending topics and suggest content ideas based on real-time search trends.',
                'You have access to Amazon product data through scraping capabilities to research products, prices, and reviews for content creation.',
                'You can transcribe YouTube videos using Supadata API to create content summaries, blog posts, and extract key insights from video content.',
                'You can generate AI images using various image generation models to create visual content for articles, posts, and products.',
            ],
            steps: [
                'When asked about content creation, provide structured and SEO-friendly content.',
                'Help with writing compelling titles, descriptions, and meta information.',
                'Suggest content improvements and optimization strategies.',
                'Provide guidance on content structure and formatting.',
                'Use Google Trends data to suggest trending topics and popular keywords for content.',
                'Research trending queries to help create timely and relevant content.',
                'Use Amazon product data to research products, compare prices, and gather product information for reviews, comparisons, or product-focused content.',
                'When provided with YouTube video URLs, transcribe the videos and create summaries, blog posts, or extract key insights for content creation.',
                'Transform video transcriptions into various content formats: blog posts, articles, social media content, or educational materials.',
                'Generate AI images when visual content is needed for articles, posts, products, or any content that would benefit from custom imagery.',
                'Create images that match the content style, brand, and target audience requirements.',
                'If the user ask for images, but the images does not exists, generate them using the AI image generation tool.',
            ],
            output: [
                'Always respond with well-formatted HTML content when creating or suggesting content.',
                'Include proper heading structure (H1, H2, H3) for better SEO.',
                'Provide actionable content recommendations with clear explanations.',
                'Format responses using appropriate HTML elements for readability.',
                'When suggesting trending content, include trend data and relevance scores.',
                'When creating content from YouTube videos, provide comprehensive summaries with key takeaways and actionable insights.',
                'Structure video-based content with clear sections: summary, key points, and practical applications.',
                'When generating images, provide detailed prompts and display the generated images with appropriate styling and metadata.',
                'Include image generation details such as style, dimensions, and prompt used for transparency and future reference.',
            ],
        );
    }

    protected function setupTools(): void
    {
        // Add comprehensive content management tools
        $this->addTool(new \Modules\Ai\Tools\ContentListTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\GetContentTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\PageListTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\PostListTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\ProductListTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\ContentSearchTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\MediaSearchTool($this->dependencies));


        // Add editing tools
        $this->addTool(new \Modules\Ai\Tools\ContentEditTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\PostEditTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\ProductEditTool($this->dependencies));

        // Add creation tools - now with simple constructors
        $this->addTool(new \Modules\Ai\Tools\CreateContentTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\CreatePostTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\CreateProductTool($this->dependencies));

        // Add RAG search tool for broader content discovery
       // $ragService = app(\Modules\Ai\Services\RagSearchService::class);
      //  $this->addTool(new \Modules\Ai\Tools\RagSearchTool($ragService, $this->dependencies));

        // Add Google Trends tool for content research and trending topics
        $this->addTool(new \Modules\Ai\Tools\GoogleTrendsTool($this->dependencies));

        // Add Amazon scraper tool for product research and content creation
        $this->addTool(new \Modules\Ai\Tools\AmazonScraperTool($this->dependencies));

        if (Config::get('modules.ai.drivers.tavily.enabled') and Config::get('modules.ai.drivers.tavily.api_key')) {
            $tavily = TavilySearchTool::make(Config::get('modules.ai.drivers.tavily.api_key'));
            $this->addTool($tavily);
        }

        // Add Supadata tool if enabled and configured
        if (Config::get('modules.ai.drivers.supadata.enabled') && Config::get('modules.ai.drivers.supadata.api_key')) {
            $this->addTool(new \Modules\Ai\Tools\SupadataTool($this->dependencies));
            // Add YouTube transcription tool with Supadata
            $this->addTool(new \Modules\Ai\Tools\YouTubeTranscriptionTool($this->dependencies));
        }

        // Add AI Image Generation tool for creating visual content
        $this->addTool(new \Modules\Ai\Tools\GenerateImageTool($this->dependencies));

        // Add content generation AI tools for automated SEO and descriptions
        $this->addTool(new \Modules\Ai\Tools\GenerateDescriptionTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\GenerateSeoMetadataTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\ContentImprovementTool($this->dependencies));

    }
}
