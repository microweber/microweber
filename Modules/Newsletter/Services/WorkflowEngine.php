<?php

namespace Modules\Newsletter\Services;

use Illuminate\Support\Facades\Log;
use Modules\Newsletter\Models\Workflow;
use Modules\Newsletter\Models\WorkflowExecution;
use Modules\Newsletter\Models\WorkflowExecutionStep;
use Modules\Newsletter\Models\WorkflowNode;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSubscriberList;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerTag;
use Modules\Cart\Models\Cart;
use Modules\Order\Models\Order;
use Exception;

class WorkflowEngine
{
    /**
     * Start a workflow execution.
     *
     * @param Workflow $workflow
     * @param array $triggerData
     * @param string $source
     * @return WorkflowExecution
     */
    public function start(Workflow $workflow, array $triggerData, string $source = WorkflowExecution::SOURCE_EVENT): WorkflowExecution
    {
        $execution = WorkflowExecution::create([
            'workflow_id' => $workflow->id,
            'execution_key' => $this->generateExecutionKey(),
            'status' => WorkflowExecution::STATUS_PENDING,
            'trigger_source' => $source,
            'trigger_data' => $triggerData,
            'total_steps' => $workflow->nodes()->count(),
        ]);

        Log::info('WorkflowEngine: Started workflow execution', [
            'execution_id' => $execution->id,
            'workflow_id' => $workflow->id,
            'source' => $source,
        ]);

        return $execution;
    }

    /**
     * Execute a workflow.
     *
     * @param WorkflowExecution $execution
     * @return void
     */
    public function execute(WorkflowExecution $execution): void
    {
        try {
            $execution->markAsStarted();
            $workflow = $execution->workflow;
            
            if (!$workflow->is_active) {
                $execution->markAsFailed('Workflow is not active');
                return;
            }

            $triggerNode = $workflow->triggerNode();
            if (!$triggerNode) {
                $execution->markAsFailed('Workflow has no trigger node');
                return;
            }

            // Execute starting from the node after the trigger
            $nextNodes = $triggerNode->getNextNodes();
            $stepNumber = 1;

            foreach ($nextNodes as $node) {
                $this->executeNode($execution, $node, $stepNumber, $execution->trigger_data);
                $stepNumber++;
            }

            $execution->markAsCompleted();
            $workflow->incrementExecution(true);

            Log::info('WorkflowEngine: Completed workflow execution', [
                'execution_id' => $execution->id,
                'workflow_id' => $workflow->id,
            ]);

        } catch (Exception $e) {
            Log::error('WorkflowEngine: Workflow execution failed', [
                'execution_id' => $execution->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $execution->markAsFailed($e->getMessage());
            $execution->workflow->incrementExecution(false);
        }
    }

    /**
     * Execute a single node.
     *
     * @param WorkflowExecution $execution
     * @param WorkflowNode $node
     * @param int $stepNumber
     * @param array $inputData
     * @return array
     */
    protected function executeNode(WorkflowExecution $execution, WorkflowNode $node, int $stepNumber, array $inputData): array
    {
        $step = WorkflowExecutionStep::create([
            'execution_id' => $execution->id,
            'node_id' => $node->id,
            'status' => WorkflowExecutionStep::STATUS_PENDING,
            'step_number' => $stepNumber,
            'input_data' => $inputData,
        ]);

        try {
            $step->markAsStarted();
            $execution->updateProgress($stepNumber, "Executing node: {$node->name}");

            $outputData = $this->processNode($node, $inputData);
            
            $step->markAsCompleted($outputData);

            // Get next nodes based on the node type and output
            $nextNodes = $this->getNextNodes($node, $outputData);
            
            $stepNumber++;
            foreach ($nextNodes as $nextNode) {
                $this->executeNode($execution, $nextNode, $stepNumber, array_merge($inputData, $outputData));
                $stepNumber++;
            }

            return $outputData;

        } catch (Exception $e) {
            Log::error('WorkflowEngine: Node execution failed', [
                'execution_id' => $execution->id,
                'node_id' => $node->id,
                'error' => $e->getMessage(),
            ]);

            $step->markAsFailed($e->getMessage(), $inputData);
            
            // Check if we should continue on error
            if ($node->getConfig('continue_on_error', false)) {
                return $inputData;
            }
            
            throw $e;
        }
    }

    /**
     * Process a node based on its type.
     *
     * @param WorkflowNode $node
     * @param array $data
     * @return array
     * @throws Exception
     */
    protected function processNode(WorkflowNode $node, array $data): array
    {
        switch ($node->node_type) {
            case WorkflowNode::TYPE_ACTION:
                return $this->processActionNode($node, $data);
            
            case WorkflowNode::TYPE_CONDITION:
                return $this->processConditionNode($node, $data);
            
            case WorkflowNode::TYPE_DELAY:
                return $this->processDelayNode($node, $data);
            
            case WorkflowNode::TYPE_SPLIT:
                return $this->processSplitNode($node, $data);
            
            case WorkflowNode::TYPE_END:
                return $data;
            
            default:
                throw new Exception("Unknown node type: {$node->node_type}");
        }
    }

    /**
     * Process an action node.
     *
     * @param WorkflowNode $node
     * @param array $data
     * @return array
     * @throws Exception
     */
    protected function processActionNode(WorkflowNode $node, array $data): array
    {
        $action = $node->node_key;

        switch ($action) {
            case WorkflowNode::KEY_SEND_EMAIL:
                return $this->actionSendEmail($node, $data);
            
            case WorkflowNode::KEY_ADD_TO_LIST:
                return $this->actionAddToList($node, $data);
            
            case WorkflowNode::KEY_REMOVE_FROM_LIST:
                return $this->actionRemoveFromList($node, $data);
            
            case WorkflowNode::KEY_APPLY_TAG:
                return $this->actionApplyTag($node, $data);
            
            case WorkflowNode::KEY_REMOVE_TAG:
                return $this->actionRemoveTag($node, $data);
            
            case WorkflowNode::KEY_UPDATE_CONTACT:
                return $this->actionUpdateContact($node, $data);
            
            case WorkflowNode::KEY_WEBHOOK:
                return $this->actionWebhook($node, $data);
            
            case WorkflowNode::KEY_CREATE_TASK:
                return $this->actionCreateTask($node, $data);
            
            case WorkflowNode::KEY_SEND_NOTIFICATION:
                return $this->actionSendNotification($node, $data);
            
            default:
                throw new Exception("Unknown action: {$action}");
        }
    }

    /**
     * Process a condition node.
     *
     * @param WorkflowNode $node
     * @param array $data
     * @return array
     */
    protected function processConditionNode(WorkflowNode $node, array $data): array
    {
        $condition = $node->node_key;
        $config = $node->config;
        $result = false;

        switch ($condition) {
            case 'field_equals':
                $field = $config['field'] ?? null;
                $value = $config['value'] ?? null;
                $result = ($data[$field] ?? null) == $value;
                break;
            
            case 'field_contains':
                $field = $config['field'] ?? null;
                $value = $config['value'] ?? null;
                $fieldValue = $data[$field] ?? '';
                $result = is_string($fieldValue) && str_contains($fieldValue, $value);
                break;
            
            case 'is_in_list':
                $listId = $config['list_id'] ?? null;
                $email = $data['email'] ?? null;
                if ($email && $listId) {
                    $subscriber = NewsletterSubscriber::where('email', $email)->first();
                    if ($subscriber) {
                        $result = NewsletterSubscriberList::where('subscriber_id', $subscriber->id)
                            ->where('list_id', $listId)
                            ->exists();
                    }
                }
                break;
            
            case 'has_tag':
                $tagId = $config['tag_id'] ?? null;
                $customerId = $data['customer_id'] ?? null;
                if ($customerId && $tagId) {
                    $result = CustomerTag::where('customer_id', $customerId)
                        ->where('tag_id', $tagId)
                        ->exists();
                }
                break;
            
            case 'opened_email':
                // Would check email open tracking
                $result = false;
                break;
            
            case 'clicked_link':
                // Would check link click tracking
                $result = false;
                break;
            
            default:
                $result = false;
        }

        return array_merge($data, [
            '_condition_result' => $result,
            '_condition_true' => $result,
            '_condition_false' => !$result,
        ]);
    }

    /**
     * Process a delay node.
     *
     * @param WorkflowNode $node
     * @param array $data
     * @return array
     */
    protected function processDelayNode(WorkflowNode $node, array $data): array
    {
        $config = $node->config;
        $delayType = $config['delay_type'] ?? 'fixed'; // fixed, until

        if ($delayType === 'fixed') {
            $delayMinutes = $config['delay_minutes'] ?? 0;
            
            // In a real implementation, this would schedule a delayed job
            // For now, we'll just log it
            Log::info('WorkflowEngine: Delay scheduled', [
                'delay_minutes' => $delayMinutes,
            ]);
        } elseif ($delayType === 'until') {
            $untilDate = $config['until_date'] ?? null;
            
            Log::info('WorkflowEngine: Delay until scheduled', [
                'until_date' => $untilDate,
            ]);
        }

        return $data;
    }

    /**
     * Process a split test node.
     *
     * @param WorkflowNode $node
     * @param array $data
     * @return array
     */
    protected function processSplitNode(WorkflowNode $node, array $data): array
    {
        $config = $node->config;
        $splitType = $config['split_type'] ?? 'percentage'; // percentage, round_robin

        if ($splitType === 'percentage') {
            $percentage = $config['percentage'] ?? 50;
            $result = (rand(1, 100) <= $percentage) ? 'A' : 'B';
        } else {
            // Round robin - would track in a more sophisticated way
            $result = (rand(0, 1) === 0) ? 'A' : 'B';
        }

        return array_merge($data, [
            '_split_result' => $result,
            '_split_A' => ($result === 'A'),
            '_split_B' => ($result === 'B'),
        ]);
    }

    /**
     * Get next nodes based on current node and output.
     *
     * @param WorkflowNode $node
     * @param array $outputData
     * @return array
     */
    protected function getNextNodes(WorkflowNode $node, array $outputData): array
    {
        $nextNodes = [];

        if ($node->node_type === WorkflowNode::TYPE_CONDITION) {
            // For condition nodes, use the result to determine path
            $result = $outputData['_condition_result'] ?? false;
            $port = $result ? 'true' : 'false';
            $nextNode = $node->getNextNodeForPort($port);
            if ($nextNode) {
                $nextNodes[] = $nextNode;
            }
        } elseif ($node->node_type === WorkflowNode::TYPE_SPLIT) {
            // For split nodes, use split result
            $result = $outputData['_split_result'] ?? 'A';
            $nextNode = $node->getNextNodeForPort($result);
            if ($nextNode) {
                $nextNodes[] = $nextNode;
            }
        } else {
            // For other nodes, get all connected nodes
            $nextNodes = $node->getNextNodes();
        }

        return $nextNodes;
    }

    /**
     * Action: Send Email.
     *
     * @param WorkflowNode $node
     * @param array $data
     * @return array
     */
    protected function actionSendEmail(WorkflowNode $node, array $data): array
    {
        $config = $node->config;
        $campaignId = $config['campaign_id'] ?? null;
        $templateId = $config['template_id'] ?? null;
        $email = $data['email'] ?? null;

        if (!$email) {
            throw new Exception('No email address provided for send_email action');
        }

        // Get or create subscriber
        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $email],
            [
                'name' => $data['first_name'] ?? ($data['name'] ?? null),
                'status' => 'subscribed',
            ]
        );

        // Queue the email if campaign_id is provided
        if ($campaignId) {
            $campaign = NewsletterCampaign::find($campaignId);
            if ($campaign) {
                // Use the existing automation service
                app(CampaignAutomationService::class)->trigger(
                    $campaign->trigger_event ?? 'manual',
                    array_merge($data, ['email' => $email])
                );
            }
        }

        Log::info('WorkflowEngine: Email action executed', [
            'email' => $email,
            'campaign_id' => $campaignId,
            'template_id' => $templateId,
        ]);

        return array_merge($data, [
            '_email_sent' => true,
            '_subscriber_id' => $subscriber->id,
        ]);
    }

    /**
     * Action: Add to List.
     *
     * @param WorkflowNode $node
     * @param array $data
     * @return array
     */
    protected function actionAddToList(WorkflowNode $node, array $data): array
    {
        $config = $node->config;
        $listId = $config['list_id'] ?? null;
        $email = $data['email'] ?? null;

        if (!$email || !$listId) {
            throw new Exception('Email and list_id required for add_to_list action');
        }

        $subscriber = NewsletterSubscriber::where('email', $email)->first();
        if (!$subscriber) {
            $subscriber = NewsletterSubscriber::create([
                'email' => $email,
                'name' => $data['first_name'] ?? ($data['name'] ?? null),
                'status' => 'subscribed',
            ]);
        }

        NewsletterSubscriberList::firstOrCreate([
            'subscriber_id' => $subscriber->id,
            'list_id' => $listId,
        ]);

        return array_merge($data, [
            '_added_to_list' => $listId,
        ]);
    }

    /**
     * Action: Remove from List.
     *
     * @param WorkflowNode $node
     * @param array $data
     * @return array
     */
    protected function actionRemoveFromList(WorkflowNode $node, array $data): array
    {
        $config = $node->config;
        $listId = $config['list_id'] ?? null;
        $email = $data['email'] ?? null;

        if (!$email || !$listId) {
            throw new Exception('Email and list_id required for remove_from_list action');
        }

        $subscriber = NewsletterSubscriber::where('email', $email)->first();
        if ($subscriber) {
            NewsletterSubscriberList::where('subscriber_id', $subscriber->id)
                ->where('list_id', $listId)
                ->delete();
        }

        return array_merge($data, [
            '_removed_from_list' => $listId,
        ]);
    }

    /**
     * Action: Apply Tag.
     *
     * @param WorkflowNode $node
     * @param array $data
     * @return array
     */
    protected function actionApplyTag(WorkflowNode $node, array $data): array
    {
        $config = $node->config;
        $tagId = $config['tag_id'] ?? null;
        $customerId = $data['customer_id'] ?? null;

        if (!$customerId || !$tagId) {
            throw new Exception('customer_id and tag_id required for apply_tag action');
        }

        CustomerTag::firstOrCreate([
            'customer_id' => $customerId,
            'tag_id' => $tagId,
        ]);

        return array_merge($data, [
            '_tag_applied' => $tagId,
        ]);
    }

    /**
     * Action: Remove Tag.
     *
     * @param WorkflowNode $node
     * @param array $data
     * @return array
     */
    protected function actionRemoveTag(WorkflowNode $node, array $data): array
    {
        $config = $node->config;
        $tagId = $config['tag_id'] ?? null;
        $customerId = $data['customer_id'] ?? null;

        if ($customerId && $tagId) {
            CustomerTag::where('customer_id', $customerId)
                ->where('tag_id', $tagId)
                ->delete();
        }

        return array_merge($data, [
            '_tag_removed' => $tagId,
        ]);
    }

    /**
     * Action: Update Contact.
     *
     * @param WorkflowNode $node
     * @param array $data
     * @return array
     */
    protected function actionUpdateContact(WorkflowNode $node, array $data): array
    {
        $config = $node->config;
        $updates = $config['updates'] ?? [];
        $customerId = $data['customer_id'] ?? null;
        $email = $data['email'] ?? null;

        if ($customerId) {
            $customer = Customer::find($customerId);
            if ($customer) {
                foreach ($updates as $field => $value) {
                    if (in_array($field, ['first_name', 'last_name', 'phone', 'company'])) {
                        $customer->$field = $value;
                    }
                }
                $customer->save();
            }
        }

        if ($email) {
            $subscriber = NewsletterSubscriber::where('email', $email)->first();
            if ($subscriber) {
                if (isset($updates['name'])) {
                    $subscriber->name = $updates['name'];
                }
                $subscriber->save();
            }
        }

        return array_merge($data, [
            '_contact_updated' => true,
        ]);
    }

    /**
     * Action: Webhook.
     *
     * @param WorkflowNode $node
     * @param array $data
     * @return array
     */
    protected function actionWebhook(WorkflowNode $node, array $data): array
    {
        $config = $node->config;
        $url = $config['url'] ?? null;
        $method = $config['method'] ?? 'POST';
        $headers = $config['headers'] ?? [];

        if (!$url) {
            throw new Exception('URL required for webhook action');
        }

        // In a real implementation, this would make an HTTP request
        // For now, just log it
        Log::info('WorkflowEngine: Webhook would be sent', [
            'url' => $url,
            'method' => $method,
            'data' => $data,
        ]);

        return array_merge($data, [
            '_webhook_sent' => true,
            '_webhook_url' => $url,
        ]);
    }

    /**
     * Action: Create Task.
     *
     * @param WorkflowNode $node
     * @param array $data
     * @return array
     */
    protected function actionCreateTask(WorkflowNode $node, array $data): array
    {
        $config = $node->config;
        $title = $config['title'] ?? 'Workflow Task';
        $description = $config['description'] ?? '';
        $dueDate = $config['due_date'] ?? null;

        // In a real implementation, this would create a task
        // For now, just log it
        Log::info('WorkflowEngine: Task would be created', [
            'title' => $title,
            'description' => $description,
            'due_date' => $dueDate,
        ]);

        return array_merge($data, [
            '_task_created' => true,
        ]);
    }

    /**
     * Action: Send Notification.
     *
     * @param WorkflowNode $node
     * @param array $data
     * @return array
     */
    protected function actionSendNotification(WorkflowNode $node, array $data): array
    {
        $config = $node->config;
        $title = $config['title'] ?? 'Workflow Notification';
        $message = $config['message'] ?? '';
        $type = $config['type'] ?? 'info'; // info, success, warning, error

        // In a real implementation, this would send a browser/push notification
        // For now, just log it
        Log::info('WorkflowEngine: Notification would be sent', [
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);

        return array_merge($data, [
            '_notification_sent' => true,
        ]);
    }

    /**
     * Generate a unique execution key.
     *
     * @return string
     */
    protected function generateExecutionKey(): string
    {
        return 'wf_' . uniqid() . '_' . time();
    }

    /**
     * Trigger workflows by event.
     *
     * @param string $event
     * @param array $data
     * @return array
     */
    public function triggerByEvent(string $event, array $data): array
    {
        $workflows = Workflow::getActiveByTrigger($event);
        $executions = [];

        foreach ($workflows as $workflow) {
            if ($workflow->shouldTrigger($data)) {
                $execution = $this->start($workflow, $data, WorkflowExecution::SOURCE_EVENT);
                $executions[] = $execution;
                
                // Queue for execution
                dispatch(function () use ($execution) {
                    $this->execute($execution);
                });
            }
        }

        return $executions;
    }

    /**
     * Get execution statistics.
     *
     * @param int $workflowId
     * @return array
     */
    public function getStatistics(int $workflowId): array
    {
        return WorkflowExecution::getStatistics($workflowId);
    }
}
