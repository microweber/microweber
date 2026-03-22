<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workflow extends Model
{
    use HasFactory;

    public $table = 'workflows';

    // Trigger types
    public const TRIGGER_TYPE_EVENT = 'event';
    public const TRIGGER_TYPE_SCHEDULE = 'schedule';
    public const TRIGGER_TYPE_MANUAL = 'manual';

    // Trigger events
    public const TRIGGER_CART_ABANDONED = 'cart_abandoned';
    public const TRIGGER_ORDER_PLACED = 'order_placed';
    public const TRIGGER_ORDER_PAID = 'order_paid';
    public const TRIGGER_USER_REGISTERED = 'user_registered';
    public const TRIGGER_USER_SUBSCRIBED = 'user_subscribed';
    public const TRIGGER_PRODUCT_VIEWED = 'product_viewed';
    public const TRIGGER_CHECKOUT_STARTED = 'checkout_started';

    public $fillable = [
        'name',
        'description',
        'trigger_type',
        'trigger_event',
        'trigger_conditions',
        'is_active',
        'last_triggered_at',
        'execution_count',
        'success_count',
        'failure_count',
    ];

    public $casts = [
        'trigger_conditions' => 'array',
        'is_active' => 'boolean',
        'last_triggered_at' => 'datetime',
        'execution_count' => 'integer',
        'success_count' => 'integer',
        'failure_count' => 'integer',
    ];

    /**
     * Get all nodes in this workflow.
     */
    public function nodes(): HasMany
    {
        return $this->hasMany(WorkflowNode::class, 'workflow_id')->orderBy('sort_order');
    }

    /**
     * Get the trigger node for this workflow.
     */
    public function triggerNode(): ?WorkflowNode
    {
        return $this->nodes()->where('node_type', 'trigger')->first();
    }

    /**
     * Get all executions for this workflow.
     */
    public function executions(): HasMany
    {
        return $this->hasMany(WorkflowExecution::class, 'workflow_id');
    }

    /**
     * Get active workflows by trigger event.
     */
    public static function getActiveByTrigger(string $event): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('is_active', true)
            ->where('trigger_type', self::TRIGGER_TYPE_EVENT)
            ->where('trigger_event', $event)
            ->get();
    }

    /**
     * Increment execution statistics.
     */
    public function incrementExecution(bool $success = true): void
    {
        $this->execution_count++;
        if ($success) {
            $this->success_count++;
        } else {
            $this->failure_count++;
        }
        $this->last_triggered_at = now();
        $this->save();
    }

    /**
     * Check if workflow should trigger based on conditions.
     */
    public function shouldTrigger(array $data): bool
    {
        $conditions = $this->trigger_conditions;

        if (empty($conditions)) {
            return true;
        }

        // Support both array and JSON string
        if (is_string($conditions)) {
            $conditions = json_decode($conditions, true);
        }

        if (!is_array($conditions)) {
            return true;
        }

        foreach ($conditions as $condition) {
            $field = $condition['field'] ?? null;
            $operator = $condition['operator'] ?? 'equals';
            $value = $condition['value'] ?? null;

            if (!$field) {
                continue;
            }

            $actualValue = $data[$field] ?? null;

            if (!$this->evaluateCondition($actualValue, $operator, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Evaluate a single condition.
     */
    protected function evaluateCondition($actualValue, string $operator, $expectedValue): bool
    {
        switch ($operator) {
            case 'equals':
                return $actualValue == $expectedValue;
            case 'not_equals':
                return $actualValue != $expectedValue;
            case 'greater_than':
                return $actualValue > $expectedValue;
            case 'less_than':
                return $actualValue < $expectedValue;
            case 'greater_than_or_equal':
                return $actualValue >= $expectedValue;
            case 'less_than_or_equal':
                return $actualValue <= $expectedValue;
            case 'contains':
                return is_string($actualValue) && str_contains($actualValue, $expectedValue);
            case 'not_contains':
                return is_string($actualValue) && !str_contains($actualValue, $expectedValue);
            case 'in':
                return is_array($expectedValue) && in_array($actualValue, $expectedValue);
            case 'not_in':
                return is_array($expectedValue) && !in_array($actualValue, $expectedValue);
            case 'exists':
                return !is_null($actualValue);
            case 'not_exists':
                return is_null($actualValue);
            default:
                return true;
        }
    }

    /**
     * Get available trigger types.
     */
    public static function getTriggerTypes(): array
    {
        return [
            self::TRIGGER_TYPE_EVENT => 'Event-based',
            self::TRIGGER_TYPE_SCHEDULE => 'Scheduled',
            self::TRIGGER_TYPE_MANUAL => 'Manual',
        ];
    }

    /**
     * Get available trigger events.
     */
    public static function getTriggerEvents(): array
    {
        return [
            self::TRIGGER_CART_ABANDONED => 'Cart Abandoned',
            self::TRIGGER_ORDER_PLACED => 'Order Placed',
            self::TRIGGER_ORDER_PAID => 'Order Paid',
            self::TRIGGER_USER_REGISTERED => 'User Registered',
            self::TRIGGER_USER_SUBSCRIBED => 'User Subscribed',
            self::TRIGGER_PRODUCT_VIEWED => 'Product Viewed',
            self::TRIGGER_CHECKOUT_STARTED => 'Checkout Started',
        ];
    }
}
