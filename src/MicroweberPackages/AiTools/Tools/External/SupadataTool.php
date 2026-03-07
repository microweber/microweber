<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Tools\External;

use Illuminate\Support\Facades\Config;
use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Supadata Tool
 *
 * Search for data using Supadata API.
 */
class SupadataTool extends BaseTool
{
    protected string $domain = 'general';
    protected array $requiredPermissions = [];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'supadata_search',
            'Search for data using Supadata API'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'query',
                type: PropertyType::STRING,
                description: 'The search query to send to Supadata',
                required: true,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        // Extract parameters from variadic args
        $params = is_array($args[0] ?? null) ? $args[0] : $args;
        $query = $params['query'] ?? '';

        $apiKey = Config::get('modules.ai.drivers.supadata.api_key');

        if (!$apiKey) {
            return 'Error: Supadata API key not configured. Please configure it in AI Settings.';
        }

        if (empty($query)) {
            return 'Error: Query parameter is required';
        }

        // This is a placeholder implementation
        // You would implement the actual Supadata API call here
        return "Supadata tool is configured and ready. API key is available. Query: {$query}";
    }
}
