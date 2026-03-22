<?php

namespace Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowNode extends Model
{
    use HasFactory;

    public $table = 'workflow_nodes';

    // Node types
    public const TYPE_TRIGGER = 'trigger';
    public const TYPE_CONDITION = 'condition';
    public const TYPE_ACTION = 'action';
    public const TYPE_DELAY = 'delay';
    public const TYPE_SPLIT = 'split';
    public const TYPE_JOIN = 'join';
    public const TYPE_END = 'end';

    // Node keys (available actions)
    public const KEY_SEND_EMAIL = 'send_email';
    public const KEY_ADD_TO_LIST = 'add_to_list';
    public const KEY_REMOVE_FROM_LIST = 'remove_from_list';
    public const KEY_APPLY_TAG = 'apply_tag';
    public const KEY_REMOVE_TAG = 'remove_tag';
    public const KEY_WAIT = 'wait';
    public const KEY_WAIT_UNTIL = 'wait_until';
    public const KEY_IF_ELSE = 'if_else';
    public const KEY_SPLIT_TEST = 'split_test';
    public const KEY_WEBHOOK = 'webhook';
    public const KEY_UPDATE_CONTACT = 'update_contact';
    public const KEY_SEND_NOTIFICATION = 'send_notification';
    public const KEY_CREATE_TASK = 'create_task';
    public const KEY_END = 'end';

    public $fillable = [
        'workflow_id',
        'node_id',
        'node_type',
        'node_key',
        'name',
        'description',
        'config',
        'position_x',
        'position_y',
        'connections',
        'sort_order',
    ];

    public $casts = [
        'config' => 'array',
        'connections' => 'array',
        'position_x' => 'integer',
        'position_y' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the workflow this node belongs to.
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class, 'workflow_id');
    }

    /**
     * Get the next node(s) based on connections.
     *
     * @return array<WorkflowNode>
     */
    public function getNextNodes(): array
    {
        if (empty($this->connections)) {
            return [];
        }

        $nextNodes = [];
        foreach ($this->connections as $connection) {
            $targetNodeId = $connection['target'] ?? null;
            if ($targetNodeId) {
                $node = self::where('node_id', $targetNodeId)->first();
                if ($node) {
                    $nextNodes[] = $node;
                }
            }
        }

        return $nextNodes;
    }

    /**
     * Get the next node for a specific output port.
     */
    public function getNextNodeForPort(string $port = 'default'): ?WorkflowNode
    {
        if (empty($this->connections)) {
            return null;
        }

        foreach ($this->connections as $connection) {
            if (($connection['sourcePort'] ?? 'default') === $port) {
                $targetNodeId = $connection['target'] ?? null;
                if ($targetNodeId) {
                    return self::where('node_id', $targetNodeId)->first();
                }
            }
        }

        return null;
    }

    /**
     * Check if this node is a trigger.
     */
    public function isTrigger(): bool
    {
        return $this->node_type === self::TYPE_TRIGGER;
    }

    /**
     * Check if this node is an action.
     */
    public function isAction(): bool
    {
        return $this->node_type === self::TYPE_ACTION;
    }

    /**
     * Check if this node is a condition.
     */
    public function isCondition(): bool
    {
        return $this->node_type === self::TYPE_CONDITION;
    }

    /**
     * Get node configuration value.
     */
    public function getConfig(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Get available node types.
     */
    public static function getNodeTypes(): array
    {
        return [
            self::TYPE_TRIGGER => 'Trigger',
            self::TYPE_CONDITION => 'Condition',
            self::TYPE_ACTION => 'Action',
            self::TYPE_DELAY => 'Delay',
            self::TYPE_SPLIT => 'Split',
            self::TYPE_JOIN => 'Join',
            self::TYPE_END => 'End',
        ];
    }

    /**
     * Get available action nodes.
     */
    public static function getActionNodes(): array
    {
        return [
            self::KEY_SEND_EMAIL => [
                'label' => 'Send Email',
                'icon' => 'heroicon-o-envelope',
                'description' => 'Send an email to the contact',
            ],
            self::KEY_ADD_TO_LIST => [
                'label' => 'Add to List',
                'icon' => 'heroicon-o-plus-circle',
                'description' => 'Add contact to a mailing list',
            ],
            self::KEY_REMOVE_FROM_LIST => [
                'label' => 'Remove from List',
                'icon' => 'heroicon-o-minus-circle',
                'description' => 'Remove contact from a mailing list',
            ],
            self::KEY_APPLY_TAG => [
                'label' => 'Apply Tag',
                'icon' => 'heroicon-o-tag',
                'description' => 'Apply a tag to the contact',
            ],
            self::KEY_REMOVE_TAG => [
                'label' => 'Remove Tag',
                'icon' => 'heroicon-o-x-circle',
                'description' => 'Remove a tag from the contact',
            ],
            self::KEY_WAIT => [
                'label' => 'Wait',
                'icon' => 'heroicon-o-clock',
                'description' => 'Wait for a specified time',
            ],
            self::KEY_WAIT_UNTIL => [
                'label' => 'Wait Until',
                'icon' => 'heroicon-o-calendar',
                'description' => 'Wait until a specific date/time',
            ],
            self::KEY_IF_ELSE => [
                'label' => 'If/Else',
                'icon' => 'heroicon-o-arrows-right-left',
                'description' => 'Branch based on conditions',
            ],
            self::KEY_SPLIT_TEST => [
                'label' => 'Split Test',
                'icon' => 'heroicon-o-chart-bar',
                'description' => 'Split contacts between paths',
            ],
            self::KEY_WEBHOOK => [
                'label' => 'Webhook',
                'icon' => 'heroicon-o-globe-alt',
                'description' => 'Send data to external webhook',
            ],
            self::KEY_UPDATE_CONTACT => [
                'label' => 'Update Contact',
                'icon' => 'heroicon-o-user',
                'description' => 'Update contact information',
            ],
            self::KEY_SEND_NOTIFICATION => [
                'label' => 'Send Notification',
                'icon' => 'heroicon-o-bell',
                'description' => 'Send browser/push notification',
            ],
            self::KEY_CREATE_TASK => [
                'label' => 'Create Task',
                'icon' => 'heroicon-o-clipboard-document-list',
                'description' => 'Create a task for the contact',
            ],
            self::KEY_END => [
                'label' => 'End',
                'icon' => 'heroicon-o-stop',
                'description' => 'End the workflow',
            ],
        ];
    }

    /**
     * Get available condition nodes.
     */
    public static function getConditionNodes(): array
    {
        return [
            'field_equals' => [
                'label' => 'Field Equals',
                'icon' => 'heroicon-o-equals',
                'description' => 'Check if a field equals a value',
            ],
            'field_contains' => [
                'label' => 'Field Contains',
                'icon' => 'heroicon-o-magnifying-glass',
                'description' => 'Check if a field contains text',
            ],
            'is_in_list' => [
                'label' => 'Is in List',
                'icon' => 'heroicon-o-list-bullet',
                'description' => 'Check if contact is in a list',
            ],
            'has_tag' => [
                'label' => 'Has Tag',
                'icon' => 'heroicon-o-tag',
                'description' => 'Check if contact has a tag',
            ],
            'opened_email' => [
                'label' => 'Opened Email',
                'icon' => 'heroicon-o-envelope-open',
                'description' => 'Check if contact opened an email',
            ],
            'clicked_link' => [
                'label' => 'Clicked Link',
                'icon' => 'heroicon-o-cursor-arrow-rays',
                'description' => 'Check if contact clicked a link',
            ],
        ];
    }
}
