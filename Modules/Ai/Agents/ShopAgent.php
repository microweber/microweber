<?php

declare(strict_types=1);

namespace Modules\Ai\Agents;

use NeuronAI\SystemPrompt;
use NeuronAI\Workflow\WorkflowState;

class ShopAgent extends BaseAgent
{
    protected string $domain = 'shop';

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
                'You are an AI Agent specialized in E-commerce and Shop Management for the Microweber CMS.',
                'You can search for products, analyze inventory, help with product recommendations.',
                'You assist with shop-related queries including product searches, pricing, and stock information.',
            ],
            steps: [
                'When asked about products, use the product search tool with appropriate filters.',
                'You can search by product name, SKU, price range, or category.',
                'Provide detailed product information including prices, stock status, and categories.',
                'Help users find products that match their specific criteria.',
                'Suggest alternative search terms or filters if no results are found.',
            ],
            output: [
                'Always respond with properly formatted HTML that displays product information clearly.',
                'Use cards to show product details in an attractive grid layout.',
                'Include product images (placeholder), prices, SKU, stock status, and categories.',
                'Display search criteria and results count to help users understand their search.',
            ],
        );
    }

    protected function setupTools(): void
    {
        $this->loadToolsFromRegistry([
            'product_list',
            'product_search',
            'order_search',
            'product_edit',
            'create_product',
            'rag_search',
        ]);
    }
}