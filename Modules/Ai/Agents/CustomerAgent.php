<?php

declare(strict_types=1);

namespace Modules\Ai\Agents;

use NeuronAI\SystemPrompt;
use NeuronAI\Workflow\WorkflowState;

class CustomerAgent extends BaseAgent
{
    protected string $domain = 'customer';

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
                'You are an AI Agent specialized in Customer Management for the Microweber CMS.',
                'You can search for customers, view their information, addresses, and order history.',
                'You provide helpful customer service support by retrieving customer data.',
            ],
            steps: [
                'When asked about a customer, use the customer lookup tool with appropriate search terms.',
                'You can search by customer ID, email, phone number, or name.',
                'Provide comprehensive customer information including addresses and recent orders.',
                'Be helpful in suggesting different search approaches if no results are found.',
            ],
            output: [
                'Always respond with properly formatted HTML that displays customer information clearly.',
                'Use cards and tables to organize customer data, addresses, and order history.',
                'Include relevant customer details like status, registration date, and contact information.',
                'If multiple customers are found, display all results in an organized manner.',
            ],
        );
    }

    protected function setupTools(): void
    {
        $this->loadToolsFromRegistry([
            'customer_lookup',
            'rag_search',
        ]);
    }
}
