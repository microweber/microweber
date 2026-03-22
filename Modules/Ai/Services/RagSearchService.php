<?php

declare(strict_types=1);

namespace Modules\Ai\Services;

use Modules\Ai\Models\AgentChatSearch;
use Modules\Customer\Models\Customer;
use Modules\Product\Models\Product;
use Modules\Content\Models\Content;
use Modules\Order\Models\Order;

class RagSearchService
{
    protected array $searchableModels = [
        'customers' => Customer::class,
        'products' => Product::class,
        'content' => Content::class,
        'orders' => Order::class,
    ];

    public function search(string $query, array $options = []): array
    {
        $results = [];
        $searchType = $options['type'] ?? 'all';
        $limit = $options['limit'] ?? 10;
        
        // Search in previous chat searches for RAG
        $historicalResults = $this->searchChatHistory($query, $limit);
        
        // Search in specific model types based on query analysis
        $contextualResults = $this->searchContextualContent($query, $searchType, $limit);
        
        // Combine and rank results
        $results = array_merge($historicalResults, $contextualResults);
        
        // Calculate relevance scores
        $results = $this->calculateRelevanceScores($query, $results);
        
        // Sort by relevance
        usort($results, fn($a, $b) => ($b['relevance'] ?? 0) <=> ($a['relevance'] ?? 0));
        
        return array_slice($results, 0, $limit);
    }

    protected function searchChatHistory(string $query, int $limit): array
    {
        $searches = AgentChatSearch::where('query', 'like', '%' . $query . '%')
            ->orWhere('results', 'like', '%' . $query . '%')
            ->orderBy('relevance_score', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $searches->map(function ($search) {
            return [
                'type' => 'chat_history',
                'source' => 'Previous Chat',
                'title' => "Previous search: {$search->query}",
                'content' => $search->results,
                'relevance' => $search->relevance_score ?? 0.5,
                'metadata' => [
                    'search_id' => $search->id,
                    'original_query' => $search->query,
                    'chat_id' => $search->chat_id,
                ],
            ];
        })->toArray();
    }

    protected function searchContextualContent(string $query, string $searchType, int $limit): array
    {
        $results = [];
        $keywords = $this->extractKeywords($query);
        
        if ($searchType === 'all' || $searchType === 'customers') {
            $results = array_merge($results, $this->searchCustomers($keywords, $limit));
        }
        
        if ($searchType === 'all' || $searchType === 'products') {
            $results = array_merge($results, $this->searchProducts($keywords, $limit));
        }
        
        if ($searchType === 'all' || $searchType === 'content') {
            $results = array_merge($results, $this->searchContent($keywords, $limit));
        }
        
        if ($searchType === 'all' || $searchType === 'orders') {
            $results = array_merge($results, $this->searchOrders($keywords, $limit));
        }
        
        return $results;
    }

    protected function searchCustomers(array $keywords, int $limit): array
    {
        $query = Customer::query();
        
        foreach ($keywords as $keyword) {
            $query->orWhere('name', 'like', '%' . $keyword . '%')
                  ->orWhere('first_name', 'like', '%' . $keyword . '%')
                  ->orWhere('last_name', 'like', '%' . $keyword . '%')
                  ->orWhere('email', 'like', '%' . $keyword . '%')
                  ->orWhere('phone', 'like', '%' . $keyword . '%');
        }
        
        $customers = $query->limit($limit)->get();
        
        return $customers->map(function ($customer) {
            return [
                'type' => 'customer',
                'source' => 'Customer Database',
                'title' => $customer->getFullName() ?: $customer->email,
                'content' => "Customer: {$customer->getFullName()}\nEmail: {$customer->getEmail()}\nPhone: {$customer->getPhone()}\nStatus: {$customer->status}",
                'relevance' => 0.8,
                'metadata' => [
                    'customer_id' => $customer->id,
                    'model_type' => 'customer',
                ],
            ];
        })->toArray();
    }

    protected function searchProducts(array $keywords, int $limit): array
    {
        $query = Product::query();
        
        foreach ($keywords as $keyword) {
            $query->orWhere('title', 'like', '%' . $keyword . '%')
                  ->orWhere('content', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%');
        }
        
        $products = $query->where('is_active', 1)->limit($limit)->get();
        
        return $products->map(function ($product) {
            return [
                'type' => 'product',
                'source' => 'Product Catalog',
                'title' => $product->title,
                'content' => "Product: {$product->title}\nDescription: " . strip_tags($product->description ?? $product->content ?? ''),
                'relevance' => 0.9,
                'metadata' => [
                    'product_id' => $product->id,
                    'model_type' => 'product',
                ],
            ];
        })->toArray();
    }

    protected function searchContent(array $keywords, int $limit): array
    {
        $query = Content::query();
        
        foreach ($keywords as $keyword) {
            $query->orWhere('title', 'like', '%' . $keyword . '%')
                  ->orWhere('content', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%');
        }
        
        $content = $query->where('is_active', 1)
                        ->whereIn('content_type', ['page', 'post'])
                        ->limit($limit)
                        ->get();
        
        return $content->map(function ($item) {
            $contentText = $item->content ?? $item->description ?? '';
            return [
                'type' => 'content',
                'source' => 'Content Pages',
                'title' => $item->title,
                'content' => "Page: {$item->title}\nContent: " . strip_tags(substr($contentText, 0, 300)),
                'relevance' => 0.7,
                'metadata' => [
                    'content_id' => $item->id,
                    'model_type' => 'content',
                    'content_type' => $item->content_type,
                ],
            ];
        })->toArray();
    }

    protected function searchOrders(array $keywords, int $limit): array
    {
        // Basic order search - you might want to expand this based on Order model structure
        $results = [];
        
        foreach ($keywords as $keyword) {
            if (is_numeric($keyword)) {
                // Search by order ID
                $order = Order::find($keyword);
                if ($order) {
                    $results[] = [
                        'type' => 'order',
                        'source' => 'Order System',
                        'title' => "Order #{$order->id}",
                        'content' => "Order #{$order->id}\nStatus: {$order->order_status}\nAmount: {$order->amount}\nDate: {$order->created_at->format('Y-m-d')}",
                        'relevance' => 0.9,
                        'metadata' => [
                            'order_id' => $order->id,
                            'model_type' => 'order',
                        ],
                    ];
                }
            }
        }
        
        return array_slice($results, 0, $limit);
    }

    protected function extractKeywords(string $query): array
    {
        // Simple keyword extraction - you could enhance with NLP libraries
        $query = strtolower($query);
        
        // Remove common stop words
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'from', 'is', 'are', 'was', 'were', 'be', 'been', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should'];
        
        $words = preg_split('/\s+/', $query);
        $keywords = array_filter($words, function ($word) use ($stopWords) {
            return strlen($word) > 2 && !in_array($word, $stopWords);
        });
        
        return array_values($keywords);
    }

    protected function calculateRelevanceScores(string $query, array $results): array
    {
        $queryWords = $this->extractKeywords($query);
        
        foreach ($results as &$result) {
            if (!isset($result['relevance'])) {
                $score = 0;
                $contentWords = $this->extractKeywords($result['content'] . ' ' . $result['title']);
                
                foreach ($queryWords as $queryWord) {
                    foreach ($contentWords as $contentWord) {
                        if (stripos($contentWord, $queryWord) !== false) {
                            $score += 0.1;
                        }
                        if ($contentWord === $queryWord) {
                            $score += 0.3;
                        }
                    }
                }
                
                $result['relevance'] = min(1.0, $score);
            }
        }
        
        return $results;
    }

    public function saveSearchResult(int $chatId, ?int $messageId, string $query, array $results, array $metadata = []): AgentChatSearch
    {
        $relevanceScore = !empty($results) ? max(array_column($results, 'relevance')) : 0;
        
        return AgentChatSearch::create([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'query' => $query,
            'results' => json_encode($results),
            'metadata' => $metadata,
            'relevance_score' => $relevanceScore,
        ]);
    }
}
