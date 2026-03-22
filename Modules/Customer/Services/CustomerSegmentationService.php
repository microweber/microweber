<?php

namespace Modules\Customer\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Customer\Models\Customer;
use Modules\Tag\Models\Tag;

/**
 * Customer Segmentation Service
 *
 * Provides tag-based customer segmentation with advanced filtering capabilities.
 *
 * @package Modules\Customer\Services
 */
class CustomerSegmentationService
{
    /**
     * @var \MicroweberPackages\App\LaravelApplication
     */
    protected $app;

    public function __construct($app = null)
    {
        $this->app = $app ?? mw();
    }

    /**
     * Get customers by tag(s).
     *
     * @param array|string $tags Tag name(s) or ID(s)
     * @param bool $matchAll If true, customers must have ALL tags (AND logic). If false, ANY tag (OR logic).
     * @param array $additionalFilters Additional filters (status, created_at range, etc.)
     * @return Collection
     */
    public function getCustomersByTags($tags, bool $matchAll = false, array $additionalFilters = []): Collection
    {
        $query = Customer::query();

        // Convert to array if single value
        $tags = is_array($tags) ? $tags : [$tags];

        // Resolve tag IDs (handles both names and IDs)
        $tagIds = $this->resolveTagIds($tags);

        if (empty($tagIds)) {
            return new Collection();
        }

        // Apply tag filter
        if ($matchAll) {
            // Must have ALL tags
            foreach ($tagIds as $tagId) {
                $query->whereHas('tags', function ($q) use ($tagId) {
                    $q->where('tagging_tags.id', $tagId);
                });
            }
        } else {
            // Must have ANY tag
            $query->whereHas('tags', function ($q) use ($tagIds) {
                $q->whereIn('tagging_tags.id', $tagIds);
            });
        }

        // Apply additional filters
        $this->applyFilters($query, $additionalFilters);

        return $query->get();
    }

    /**
     * Get customers without any tags.
     *
     * @param array $filters Additional filters
     * @return Collection
     */
    public function getCustomersWithoutTags(array $filters = []): Collection
    {
        $query = Customer::whereDoesntHave('tags');

        $this->applyFilters($query, $filters);

        return $query->get();
    }

    /**
     * Get customer segments grouped by tag.
     *
     * @return array
     */
    public function getSegmentsGroupedByTag(): array
    {
        $segments = [];

        $tags = Tag::withCount(['customers'])
            ->having('customers_count', '>', 0)
            ->get();

        foreach ($tags as $tag) {
            $segments[] = [
                'tag_id' => $tag->id,
                'tag_name' => $tag->name,
                'tag_slug' => $tag->slug,
                'customer_count' => $tag->customers_count,
                'percentage' => 0, // Calculated below
            ];
        }

        // Calculate percentages
        $totalCustomers = Customer::count();
        if ($totalCustomers > 0) {
            foreach ($segments as &$segment) {
                $segment['percentage'] = round(($segment['customer_count'] / $totalCustomers) * 100, 2);
            }
        }

        return $segments;
    }

    /**
     * Get customer analytics by tags.
     *
     * @return array
     */
    public function getTagAnalytics(): array
    {
        $analytics = [
            'total_tags' => Tag::count(),
            'tags_in_use' => Customer::whereHas('tags')->distinct('id')->count('id'),
            'customers_with_tags' => Customer::has('tags')->count(),
            'customers_without_tags' => Customer::doesntHave('tags')->count(),
            'top_tags' => [],
            'tag_distribution' => [],
        ];

        // Get top 10 tags by customer count
        $topTags = Tag::withCount('customers')
            ->orderBy('customers_count', 'desc')
            ->limit(10)
            ->get();

        foreach ($topTags as $tag) {
            $analytics['top_tags'][] = [
                'id' => $tag->id,
                'name' => $tag->name,
                'customer_count' => $tag->customers_count,
            ];
        }

        // Get distribution of customers by tag count
        // Note: Simplified query for better database compatibility
        $distribution = DB::table('customer_tags')
            ->select('customer_id')
            ->selectRaw('COUNT(*) as tag_count')
            ->groupBy('customer_id')
            ->get();

        $tagCounts = [];
        foreach ($distribution as $item) {
            $count = $item->tag_count;
            if (!isset($tagCounts[$count])) {
                $tagCounts[$count] = 0;
            }
            $tagCounts[$count]++;
        }
        $analytics['tag_distribution'] = $tagCounts;

        return $analytics;
    }

    /**
     * Create a segment by combining multiple criteria.
     *
     * @param string $name Segment name
     * @param array $criteria Criteria for segment
     * @return array
     */
    public function createSegment(string $name, array $criteria): array
    {
        $query = Customer::query();

        // Apply tag criteria
        if (!empty($criteria['tags'])) {
            $tagIds = $this->resolveTagIds($criteria['tags']);

        if (!empty($tagIds)) {
            if ($criteria['tag_match'] === 'all') {
                foreach ($tagIds as $tagId) {
                    $query->whereHas('tags', function ($q) use ($tagId) {
                        $q->where('tagging_tags.id', $tagId);
                    });
                }
            } else {
                $query->whereHas('tags', function ($q) use ($tagIds) {
                    $q->whereIn('tagging_tags.id', $tagIds);
                });
            }
        }
    }

        // Apply status filter
        if (!empty($criteria['status'])) {
            $query->where('status', $criteria['status']);
        }

        // Apply date range filters
        if (!empty($criteria['created_after'])) {
            $query->where('created_at', '>=', $criteria['created_after']);
        }

        if (!empty($criteria['created_before'])) {
            $query->where('created_at', '<=', $criteria['created_before']);
        }

        // Apply order-related filters
        if (!empty($criteria['has_orders'])) {
            if ($criteria['has_orders']) {
                $query->has('orders');
            } else {
                $query->doesntHave('orders');
            }
        }

        if (!empty($criteria['min_orders'])) {
            $query->has('orders', '>=', $criteria['min_orders']);
        }

        // Get results
        $customers = $query->get();

        return [
            'name' => $name,
            'criteria' => $criteria,
            'count' => $customers->count(),
            'customer_ids' => $customers->pluck('id')->toArray(),
        ];
    }

    /**
     * Export segment to CSV/JSON.
     *
     * @param array $segment
     * @param string $format
     * @return string
     */
    public function exportSegment(array $segment, string $format = 'json'): string
    {
        $customers = Customer::whereIn('id', $segment['customer_ids'] ?? [])
            ->with('tags')
            ->get();

        if ($format === 'csv') {
            return $this->exportToCsv($customers);
        }

        return $customers->toJson();
    }

    /**
     * Get customers with similar tags (for recommendations).
     *
     * @param int $customerId
     * @param int $limit
     * @return Collection
     */
    public function getSimilarCustomers(int $customerId, int $limit = 10): Collection
    {
        $customer = Customer::find($customerId);

        if (!$customer) {
            return new Collection();
        }

        $tagIds = $customer->tags->pluck('id')->toArray();

        if (empty($tagIds)) {
            return new Collection();
        }

        return Customer::where('id', '!=', $customerId)
        ->whereHas('tags', function ($q) use ($tagIds) {
            $q->whereIn('tagging_tags.id', $tagIds);
        })
        ->withCount(['tags as matching_tags_count' => function ($q) use ($tagIds) {
            $q->whereIn('tagging_tags.id', $tagIds);
        }])
        ->orderBy('matching_tags_count', 'desc')
        ->limit($limit)
        ->get();
    }

    /**
     * Bulk assign tags to customers.
     *
     * @param array $customerIds
     * @param array $tagIds
     * @return int Number of customers affected
     */
    public function bulkAssignTags(array $customerIds, array $tagIds): int
    {
        $count = 0;

        foreach ($customerIds as $customerId) {
            $customer = Customer::find($customerId);
            if ($customer) {
                $customer->addTags($tagIds);
                $count++;
            }
        }

        Log::info('Bulk tag assignment completed', [
            'customers' => $count,
            'tags' => $tagIds,
        ]);

        return $count;
    }

    /**
     * Bulk remove tags from customers.
     *
     * @param array $customerIds
     * @param array $tagIds
     * @return int Number of customers affected
     */
    public function bulkRemoveTags(array $customerIds, array $tagIds): int
    {
        $count = 0;

        foreach ($customerIds as $customerId) {
            $customer = Customer::find($customerId);
            if ($customer) {
                $customer->removeTags($tagIds);
                $count++;
            }
        }

        Log::info('Bulk tag removal completed', [
            'customers' => $count,
            'tags' => $tagIds,
        ]);

        return $count;
    }

    /**
     * Resolve tag IDs from names or IDs.
     *
     * @param array $tags
     * @return array
     */
    protected function resolveTagIds(array $tags): array
    {
        $tagIds = [];

        foreach ($tags as $tag) {
            if (is_numeric($tag)) {
                $tagIds[] = (int) $tag;
            } else {
                $foundTag = Tag::where('name', $tag)
                    ->orWhere('slug', $tag)
                    ->first();
                if ($foundTag) {
                    $tagIds[] = $foundTag->id;
                }
            }
        }

        return array_unique($tagIds);
    }

    /**
     * Apply additional filters to query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return void
     */
    protected function applyFilters($query, array $filters): void
    {
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['created_after'])) {
            $query->where('created_at', '>=', $filters['created_after']);
        }

        if (!empty($filters['created_before'])) {
            $query->where('created_at', '<=', $filters['created_before']);
        }

        if (!empty($filters['has_orders'])) {
            if ($filters['has_orders'] === true) {
                $query->has('orders');
            } else {
                $query->doesntHave('orders');
            }
        }

        if (!empty($filters['min_orders'])) {
            $query->has('orders', '>=', $filters['min_orders']);
        }

        if (!empty($filters['max_orders'])) {
            $query->has('orders', '<=', $filters['max_orders']);
        }

        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', $search)
                    ->orWhere('last_name', 'LIKE', $search)
                    ->orWhere('email', 'LIKE', $search)
                    ->orWhere('phone', 'LIKE', $search);
            });
        }
    }

    /**
     * Export customers to CSV format.
     *
     * @param Collection $customers
     * @return string
     */
    protected function exportToCsv(Collection $customers): string
    {
        $output = fopen('php://temp', 'r+');

        // Headers
        fputcsv($output, [
            'ID', 'Name', 'First Name', 'Last Name', 'Email', 'Phone',
            'Status', 'Tags', 'Created At'
        ]);

        // Data
        foreach ($customers as $customer) {
            fputcsv($output, [
                $customer->id,
                $customer->name,
                $customer->first_name,
                $customer->last_name,
                $customer->email,
                $customer->phone,
                $customer->status,
                $customer->tag_list,
                $customer->created_at,
            ]);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }
}
