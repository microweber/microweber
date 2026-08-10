<?php

declare(strict_types=1);

namespace Modules\Newsletter\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;

use Illuminate\Support\Str;
use Modules\Newsletter\Models\NewsletterAutomationQueue;
use Modules\Newsletter\Models\WorkflowExecution;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class NewsletterAutomationStatusTool extends BaseTool
{
    protected string $domain = 'newsletter';

    protected array $requiredPermissions = ['view newsletters'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'newsletter_automation_status',
            'Review newsletter automation queue health and recent workflow execution status.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'view',
                type: PropertyType::STRING,
                description: 'Select "summary", "queue", or "workflow". Default is "summary".',
                required: false,
            ),
            new ToolProperty(
                name: 'status',
                type: PropertyType::STRING,
                description: 'Optional queue or workflow execution status filter.',
                required: false,
            ),
            new ToolProperty(
                name: 'trigger_event',
                type: PropertyType::STRING,
                description: 'Optional automation trigger filter such as "cart_abandoned" or "user_registered".',
                required: false,
            ),
            new ToolProperty(
                name: 'workflow_id',
                type: PropertyType::INTEGER,
                description: 'Optional workflow ID filter for execution status.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of queue items or executions to return (1-50). Default is 10.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $view = trim((string) ($args['view'] ?? 'summary'));
        $status = trim((string) ($args['status'] ?? ''));
        $triggerEvent = trim((string) ($args['trigger_event'] ?? ''));
        $workflowId = isset($args['workflow_id']) ? (int) $args['workflow_id'] : null;
        $limit = max(1, min(50, (int) ($args['limit'] ?? 10)));

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view newsletter automation status.');
        }

        try {
            return match ($view) {
                'queue' => $this->queueView($status, $triggerEvent, $limit),
                'workflow' => $this->workflowView($status, $workflowId, $limit),
                default => $this->summaryView($status, $triggerEvent, $workflowId, $limit),
            };
        } catch (\Throwable $exception) {
            return $this->handleError('Error looking up newsletter automation status: ' . $exception->getMessage());
        }
    }

    private function summaryView(string $status, string $triggerEvent, ?int $workflowId, int $limit): string
    {
        $queueQuery = NewsletterAutomationQueue::query();

        if ($status !== '' && in_array($status, [
            NewsletterAutomationQueue::STATUS_PENDING,
            NewsletterAutomationQueue::STATUS_SENT,
            NewsletterAutomationQueue::STATUS_FAILED,
            NewsletterAutomationQueue::STATUS_CANCELED,
        ], true)) {
            $queueQuery->where('status', $status);
        }

        if ($triggerEvent !== '') {
            $queueQuery->where('trigger_event', $triggerEvent);
        }

        $workflowStats = WorkflowExecution::getStatistics($workflowId);

        $queueSummaryTable = $this->formatAsHtmlTable(
            [[
                'pending' => (string) (clone $queueQuery)->where('status', NewsletterAutomationQueue::STATUS_PENDING)->count(),
                'ready_to_send' => (string) (clone $queueQuery)->where('status', NewsletterAutomationQueue::STATUS_PENDING)->where('scheduled_at', '<=', now())->count(),
                'sent' => (string) (clone $queueQuery)->where('status', NewsletterAutomationQueue::STATUS_SENT)->count(),
                'failed' => (string) (clone $queueQuery)->where('status', NewsletterAutomationQueue::STATUS_FAILED)->count(),
                'canceled' => (string) (clone $queueQuery)->where('status', NewsletterAutomationQueue::STATUS_CANCELED)->count(),
            ]],
            [
                'pending' => 'Pending',
                'ready_to_send' => 'Ready to send',
                'sent' => 'Sent',
                'failed' => 'Failed',
                'canceled' => 'Canceled',
            ],
            '',
            'newsletter-automation-queue-summary'
        );

        $workflowSummaryTable = $this->formatAsHtmlTable(
            [[
                'total' => (string) $workflowStats['total'],
                'pending' => (string) $workflowStats['pending'],
                'running' => (string) $workflowStats['running'],
                'completed' => (string) $workflowStats['completed'],
                'failed' => (string) $workflowStats['failed'],
                'cancelled' => (string) $workflowStats['cancelled'],
                'success_rate' => number_format((float) $workflowStats['success_rate'], 1) . '%',
            ]],
            [
                'total' => 'Total',
                'pending' => 'Pending',
                'running' => 'Running',
                'completed' => 'Completed',
                'failed' => 'Failed',
                'cancelled' => 'Cancelled',
                'success_rate' => 'Success rate',
            ],
            '',
            'newsletter-workflow-summary'
        );

        return '<h4>Newsletter automation queue summary</h4>'
            . $queueSummaryTable
            . '<h4>Workflow execution summary</h4>'
            . $workflowSummaryTable
            . '<h5>Recent queue items</h5>'
            . $this->queueTable($status, $triggerEvent, $limit)
            . '<h5>Recent workflow executions</h5>'
            . $this->workflowTable($status, $workflowId, $limit);
    }

    private function queueView(string $status, string $triggerEvent, int $limit): string
    {
        return '<h4>Newsletter automation queue</h4>' . $this->queueTable($status, $triggerEvent, $limit);
    }

    private function workflowView(string $status, ?int $workflowId, int $limit): string
    {
        return '<h4>Newsletter workflow executions</h4>' . $this->workflowTable($status, $workflowId, $limit);
    }

    private function queueTable(string $status, string $triggerEvent, int $limit): string
    {
        $query = NewsletterAutomationQueue::query()->with('campaign');

        if ($status !== '' && in_array($status, [
            NewsletterAutomationQueue::STATUS_PENDING,
            NewsletterAutomationQueue::STATUS_SENT,
            NewsletterAutomationQueue::STATUS_FAILED,
            NewsletterAutomationQueue::STATUS_CANCELED,
        ], true)) {
            $query->where('status', $status);
        }

        if ($triggerEvent !== '') {
            $query->where('trigger_event', $triggerEvent);
        }

        $rows = $query
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(function (NewsletterAutomationQueue $queueItem): array {
                return [
                    'campaign' => $queueItem->campaign?->name ?: ('Campaign #' . $queueItem->campaign_id),
                    'trigger' => (string) $queueItem->trigger_event,
                    'status' => (string) $queueItem->status,
                    'scheduled' => $queueItem->scheduled_at ? $queueItem->scheduled_at->toDateTimeString() : 'Unknown',
                    'sent' => $queueItem->sent_at ? $queueItem->sent_at->toDateTimeString() : 'Not sent',
                    'error' => $this->sanitizeMessage((string) ($queueItem->error_message ?? '')),
                ];
            })
            ->all();

        return $this->formatAsHtmlTable(
            $rows,
            [
                'campaign' => 'Campaign',
                'trigger' => 'Trigger',
                'status' => 'Status',
                'scheduled' => 'Scheduled at',
                'sent' => 'Sent at',
                'error' => 'Error',
            ],
            'No automation queue items matched the current filters.',
            'newsletter-automation-queue-results'
        );
    }

    private function workflowTable(string $status, ?int $workflowId, int $limit): string
    {
        $query = WorkflowExecution::query()->with('workflow');

        if ($workflowId !== null && $workflowId > 0) {
            $query->where('workflow_id', $workflowId);
        }

        if ($status !== '' && in_array($status, [
            WorkflowExecution::STATUS_PENDING,
            WorkflowExecution::STATUS_RUNNING,
            WorkflowExecution::STATUS_COMPLETED,
            WorkflowExecution::STATUS_FAILED,
            WorkflowExecution::STATUS_CANCELLED,
        ], true)) {
            $query->where('status', $status);
        }

        $rows = $query
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(function (WorkflowExecution $execution): array {
                return [
                    'workflow' => $execution->workflow?->name ?: ('Workflow #' . $execution->workflow_id),
                    'status' => (string) $execution->status,
                    'trigger' => (string) $execution->trigger_source,
                    'progress' => (string) $execution->current_step . '/' . (string) $execution->total_steps,
                    'duration' => $execution->getDuration() !== null ? $execution->getDuration() . 's' : 'Not started',
                    'error' => $this->sanitizeMessage((string) ($execution->error_message ?? '')),
                ];
            })
            ->all();

        return $this->formatAsHtmlTable(
            $rows,
            [
                'workflow' => 'Workflow',
                'status' => 'Status',
                'trigger' => 'Trigger source',
                'progress' => 'Progress',
                'duration' => 'Duration',
                'error' => 'Error',
            ],
            'No workflow executions matched the current filters.',
            'newsletter-workflow-results'
        );
    }

    private function sanitizeMessage(string $message): string
    {
        if ($message === '') {
            return 'None';
        }

        $sanitized = preg_replace('/(password|secret|token|api[_-]?key)\s*[:=]\s*[^,\s]+/i', '$1=[redacted]', $message) ?? $message;

        return Str::limit($sanitized, 80);
    }
}
