<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Modules\Ai\Services\RagSearchService;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class RagSearchTool extends BaseTool
{
    protected string $domain = 'search';
    protected array $requiredPermissions = [];

    public function __construct(
        protected RagSearchService $ragService,
        protected array $dependencies = []
    ) {
        parent::__construct(
            'rag_search',
            'Search across all Microweber content using RAG (Retrieval-Augmented Generation) for comprehensive information retrieval.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'query',
                type: PropertyType::from('string'),
                description: 'The search query to find relevant information across customers, products, content, and previous conversations.',
                required: true,
            ),
            new ToolProperty(
                name: 'search_type',
                type: PropertyType::from('string'),
                description: 'Type of content to search: "all", "customers", "products", "content", "orders", or "chat_history".',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::from('integer'),
                description: 'Maximum number of results to return (default: 10, max: 50).',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        // Extract parameters - args could be passed as an associative array
        $params = is_array($args[0] ?? null) ? $args[0] : $args;
        $query = $params['query'] ?? '';
        $search_type = $params['search_type'] ?? 'all';
        $limit = $params['limit'] ?? 10;
        
        if (empty($query)) {
            return $this->handleError('Search query cannot be empty.');
        }

        // Validate limit
        $limit = max(1, min(50, $limit));

        try {
            // Perform RAG search
            $results = $this->ragService->search($query, [
                'type' => $search_type,
                'limit' => $limit,
            ]);

            if (empty($results)) {
                return $this->handleError("No relevant information found for: '{$query}'");
            }

            // Save search for future RAG
            if (isset($this->state)) {
                $chatId = $this->state->get('chat_id');
                $messageId = $this->state->get('message_id');
                
                if ($chatId) {
                    $this->ragService->saveSearchResult(
                        $chatId,
                        $messageId,
                        $query,
                        $results,
                        ['search_type' => $search_type, 'limit' => $limit]
                    );
                }
            }

            return $this->formatSearchResults($results, $query, $search_type);

        } catch (\Exception $e) {
            return $this->handleError('Error performing search: ' . $e->getMessage());
        }
    }

    private function formatSearchResults(array $results, string $query, string $searchType): string
    {
        $html = '<div class="rag-search-results">';
        
        // Search summary
        $html .= '<div class="alert alert-info mb-3">';
        $html .= '<h6><i class="fas fa-search me-2"></i>Search Results</h6>';
        $html .= '<p><strong>Query:</strong> ' . htmlspecialchars($query) . '</p>';
        $html .= '<p><strong>Search Type:</strong> ' . ucfirst($searchType) . '</p>';
        $html .= '<p><strong>Found:</strong> ' . count($results) . ' relevant results</p>';
        $html .= '</div>';

        // Group results by type
        $groupedResults = [];
        foreach ($results as $result) {
            $type = $result['type'] ?? 'unknown';
            $groupedResults[$type][] = $result;
        }

        foreach ($groupedResults as $type => $typeResults) {
            $html .= '<div class="result-group mb-4">';
            $html .= '<h5 class="border-bottom pb-2">';
            
            switch ($type) {
                case 'customer':
                    $html .= '<i class="fas fa-users text-primary me-2"></i>Customers';
                    break;
                case 'product':
                    $html .= '<i class="fas fa-shopping-cart text-success me-2"></i>Products';
                    break;
                case 'content':
                    $html .= '<i class="fas fa-file-alt text-warning me-2"></i>Content';
                    break;
                case 'order':
                    $html .= '<i class="fas fa-receipt text-info me-2"></i>Orders';
                    break;
                case 'chat_history':
                    $html .= '<i class="fas fa-history text-secondary me-2"></i>Previous Conversations';
                    break;
                default:
                    $html .= '<i class="fas fa-question-circle text-muted me-2"></i>' . ucfirst($type);
            }
            
            $html .= ' (' . count($typeResults) . ')';
            $html .= '</h5>';

            foreach ($typeResults as $result) {
                $html .= '<div class="card mb-3">';
                $html .= '<div class="card-body">';
                
                // Title and source
                $html .= '<div class="d-flex justify-content-between align-items-start mb-2">';
                $html .= '<h6 class="card-title mb-0">' . htmlspecialchars($result['title']) . '</h6>';
                $html .= '<small class="text-muted">' . htmlspecialchars($result['source']) . '</small>';
                $html .= '</div>';
                
                // Content
                $content = strip_tags($result['content']);
                if (strlen($content) > 300) {
                    $content = substr($content, 0, 300) . '...';
                }
                $html .= '<p class="card-text">' . htmlspecialchars($content) . '</p>';
                
                // Relevance and metadata
                $html .= '<div class="d-flex justify-content-between align-items-center">';
                
                $relevance = ($result['relevance'] ?? 0) * 100;
                $badgeClass = $relevance >= 80 ? 'success' : ($relevance >= 60 ? 'warning' : 'secondary');
                $html .= '<span class="badge bg-' . $badgeClass . '">Relevance: ' . number_format($relevance, 1) . '%</span>';
                
                if (isset($result['metadata'])) {
                    $html .= '<small class="text-muted">';
                    if (isset($result['metadata']['customer_id'])) {
                        $html .= 'Customer ID: ' . $result['metadata']['customer_id'];
                    } elseif (isset($result['metadata']['product_id'])) {
                        $html .= 'Product ID: ' . $result['metadata']['product_id'];
                    } elseif (isset($result['metadata']['content_id'])) {
                        $html .= 'Content ID: ' . $result['metadata']['content_id'];
                    } elseif (isset($result['metadata']['order_id'])) {
                        $html .= 'Order ID: ' . $result['metadata']['order_id'];
                    }
                    $html .= '</small>';
                }
                
                $html .= '</div>';
                $html .= '</div></div>';
            }
            
            $html .= '</div>';
        }

        $html .= '<div class="alert alert-light mt-3">';
        $html .= '<small class="text-muted">';
        $html .= '<i class="fas fa-info-circle me-1"></i>';
        $html .= 'Results are ranked by relevance and include content from your database and previous conversations. ';
        $html .= 'This search will be saved to improve future searches.';
        $html .= '</small>';
        $html .= '</div>';

        $html .= '</div>';
        
        return $html;
    }
}
