<?php

declare(strict_types=1);

namespace Modules\Ai\Nodes;

use Modules\Ai\Events\ProgressEvent;
use Modules\Ai\Events\SpecializedAgentExecutionEvent;
use Modules\Ai\Services\AgentFactory;
use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Workflow\Node;
use NeuronAI\Workflow\StopEvent;
use NeuronAI\Workflow\WorkflowState;

class AgentExecutionNode extends Node
{
    public function __construct(
        protected AgentFactory $agentFactory
    ) {
    }

    public function __invoke(SpecializedAgentExecutionEvent $event, WorkflowState $state): \Generator|StopEvent
    {
        $agentType = $event->agentType;
        $userQuery = $event->userQuery;
        $context = $event->context;

        yield new ProgressEvent("🚀 Executing {$agentType} agent...");

        try {
            // Get the specialized agent
            $agent = $this->agentFactory->agent(
                name: $agentType,
                providerName: config('modules.ai.default_driver'),
                model: config('modules.ai.drivers.' . config('modules.ai.default_driver') . '.model')
            );

            // Set any additional context if provided
            if (!empty($context)) {
                yield new ProgressEvent("📋 Using additional context: " . json_encode($context));
            }

            yield new ProgressEvent("💬 Processing your request with {$agentType} agent...");

            // Execute the agent with the user query via NeuronAI chat (supports tool-calling)
            $chatResponse = $agent->chat(new UserMessage($userQuery))->getMessage();
            $response = $chatResponse->getContent();

            yield new ProgressEvent("✅ {$agentType} agent completed successfully");

            // Store the response in state
            $state->set('agent_response', $response);
            $state->set('executed_agent', $agentType);
            
            // Store routing information for reference
            $routingInfo = $state->get('agent_routing', []);
            $state->set('execution_summary', [
                'agent_type' => $agentType,
                'confidence' => $routingInfo['confidence'] ?? 0,
                'reasoning' => $routingInfo['reasoning'] ?? '',
                'success' => true,
                'response_length' => strlen($response)
            ]);

        } catch (\Exception $e) {
            yield new ProgressEvent("❌ Error executing {$agentType} agent: " . $e->getMessage());
            
            // Fallback to general response
            $fallbackResponse = $this->getFallbackResponse($agentType, $userQuery, $e);
            $state->set('agent_response', $fallbackResponse);
            $state->set('executed_agent', 'general_fallback');
            $state->set('execution_summary', [
                'agent_type' => $agentType,
                'success' => false,
                'error' => $e->getMessage(),
                'fallback_used' => true
            ]);

            yield new ProgressEvent("🔄 Provided fallback response");
        }

        return new StopEvent();
    }

    protected function getFallbackResponse(string $agentType, string $userQuery, \Exception $error): string
    {
        $errorMessage = htmlspecialchars($error->getMessage());
        
        return <<<HTML
<div class="alert alert-warning">
    <h5><i class="fas fa-exclamation-triangle"></i> Agent Execution Issue</h5>
    <p>There was an issue executing the <strong>{$agentType}</strong> agent for your request:</p>
    <blockquote class="blockquote">"{$userQuery}"</blockquote>
    
    <details class="mt-3">
        <summary>Technical Details</summary>
        <small class="text-muted">Error: {$errorMessage}</small>
    </details>
</div>

<div class="alert alert-info">
    <h6><i class="fas fa-lightbulb"></i> What you can try:</h6>
    <ul class="mb-0">
        <li>Rephrase your question with more specific details</li>
        <li>Try breaking down complex requests into simpler parts</li>
        <li>Check if the system modules are properly configured</li>
        <li>Contact support if the issue persists</li>
    </ul>
</div>

<div class="card">
    <div class="card-header">
        <h6><i class="fas fa-info-circle"></i> Available Help</h6>
    </div>
    <div class="card-body">
        <p>Here are some things I can help you with in the meantime:</p>
        <div class="row">
            <div class="col-md-4">
                <strong>Content Management:</strong>
                <ul class="small">
                    <li>Creating SEO-friendly content</li>
                    <li>Writing blog posts and pages</li>
                    <li>Content optimization tips</li>
                </ul>
            </div>
            <div class="col-md-4">
                <strong>Shop Management:</strong>
                <ul class="small">
                    <li>Product catalog management</li>
                    <li>Order processing</li>
                    <li>Inventory tracking</li>
                </ul>
            </div>
            <div class="col-md-4">
                <strong>Customer Management:</strong>
                <ul class="small">
                    <li>Customer data management</li>
                    <li>User account assistance</li>
                    <li>Customer support workflows</li>
                </ul>
            </div>
        </div>
    </div>
</div>
HTML;
    }
}
