<?php

namespace Modules\Newsletter\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Log;
use Modules\Newsletter\Models\NewsletterAutomationQueue;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterTemplate;
use Modules\Newsletter\Senders\NewsletterMailSender;
use Modules\Newsletter\Support\NewsletterPlaceholderSyntax;

class ProcessTriggeredEmail implements ShouldQueue
{
    use Queueable;

    public int $queueItemId;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(int $queueItemId)
    {
        $this->queueItemId = $queueItemId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $queueItem = NewsletterAutomationQueue::find($this->queueItemId);

        if (!$queueItem) {
            Log::warning('ProcessTriggeredEmail: Queue item not found', [
                'queue_item_id' => $this->queueItemId,
            ]);
            return;
        }

        if ($queueItem->status !== NewsletterAutomationQueue::STATUS_PENDING) {
            Log::warning('ProcessTriggeredEmail: Queue item is not pending', [
                'queue_item_id' => $this->queueItemId,
                'status' => $queueItem->status,
            ]);
            return;
        }

        $campaign = NewsletterCampaign::find($queueItem->campaign_id);
        if (!$campaign) {
            $queueItem->markAsFailed('Campaign not found');
            return;
        }

        $sender = NewsletterSenderAccount::find($campaign->sender_account_id);
        if (!$sender) {
            $queueItem->markAsFailed('Sender account not found');
            return;
        }

        // Get subscriber
        $subscriber = $queueItem->subscriber;
        if (!$subscriber) {
            // Create a temporary subscriber for this email
            $subscriber = NewsletterSubscriber::firstOrCreate(
                ['email' => $queueItem->email],
                [
                    'email' => $queueItem->email,
                    'status' => 'subscribed',
                ]
            );
        }

        // Check if already sent to this subscriber for this campaign
        $alreadySent = NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)
            ->where('subscriber_id', $subscriber->id)
            ->exists();

        if ($alreadySent) {
            $queueItem->markAsFailed('Email already sent to this subscriber');
            return;
        }

        // Get template
        $templateArray = [];
        if ($campaign->email_content_type === 'design') {
            $template = NewsletterTemplate::find($campaign->email_template_id);
            if (!$template) {
                $queueItem->markAsFailed('Template not found');
                return;
            }
            $templateArray = $template->toArray();
        } elseif (!empty($campaign->email_content_html)) {
            $templateArray['text'] = $campaign->email_content_html;
        } else {
            $queueItem->markAsFailed('No email content found');
            return;
        }

        // Create send log entry
        $sendLog = new NewsletterCampaignsSendLog();
        $sendLog->campaign_id = $campaign->id;
        $sendLog->subscriber_id = $subscriber->id;
        $sendLog->is_sent = false;
        $sendLog->save();

        try {
            // Build template with event data
            $templateArray = $this->injectEventData($templateArray, $queueItem->event_data);

            $newsletterMailSender = new NewsletterMailSender();
            $newsletterMailSender->setCampaign($campaign->toArray());
            $newsletterMailSender->setSubscriber($subscriber->toArray());
            $newsletterMailSender->setSender($sender->toArray());
            $newsletterMailSender->setTemplate($templateArray);

            $sendMailResponse = $newsletterMailSender->sendMail();

            if ($sendMailResponse['success']) {
                $sendLog->is_sent = true;
                $sendLog->save();

                $queueItem->markAsSent();

                Log::info('ProcessTriggeredEmail: Email sent successfully', [
                    'queue_item_id' => $this->queueItemId,
                    'campaign_id' => $campaign->id,
                    'email' => $queueItem->email,
                ]);
            } else {
                $sendLog->is_sent = false;
                $sendLog->save();

                $queueItem->markAsFailed($sendMailResponse['message'] ?? 'Unknown error');

                Log::error('ProcessTriggeredEmail: Failed to send email', [
                    'queue_item_id' => $this->queueItemId,
                    'error' => $sendMailResponse['message'] ?? 'Unknown error',
                ]);
            }
        } catch (\Exception $e) {
            $sendLog->is_sent = false;
            $sendLog->save();

            $queueItem->markAsFailed($e->getMessage());

            Log::error('ProcessTriggeredEmail: Exception while sending email', [
                'queue_item_id' => $this->queueItemId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Inject event data into template variables.
     *
     * @param array $template
     * @param array|null $eventData
     * @return array
     */
    protected function injectEventData(array $template, ?array $eventData): array
    {
        if (!$eventData) {
            return $template;
        }

        // Create template text with event data replacements
        $text = $template['text'] ?? '';

        // Replace common event data variables
        $replacements = [
            'cart_total' => (string) ($eventData['cart_total'] ?? ''),
            'cart_total_formatted' => isset($eventData['cart_total']) ? '$' . number_format($eventData['cart_total'], 2) : '',
            'item_count' => (string) ($eventData['item_count'] ?? ''),
            'order_reference' => (string) ($eventData['order_reference'] ?? ''),
            'order_total' => (string) ($eventData['order_total'] ?? ''),
            'order_total_formatted' => isset($eventData['order_total']) ? '$' . number_format($eventData['order_total'], 2) : '',
            'first_name' => (string) ($eventData['first_name'] ?? ''),
            'last_name' => (string) ($eventData['last_name'] ?? ''),
        ];

        foreach ($replacements as $token => $value) {
            $text = NewsletterPlaceholderSyntax::replaceTokenVariants($text, $token, $value);
        }

        // Handle cart items specially
        if (!empty($eventData['cart_items']) && is_array($eventData['cart_items'])) {
            $cartItemsHtml = $this->renderCartItems($eventData['cart_items']);
            $text = NewsletterPlaceholderSyntax::replaceTokenVariants($text, 'cart_items', $cartItemsHtml);
        }

        $template['text'] = $text;

        return $template;
    }

    /**
     * Render cart items as HTML.
     *
     * @param array $items
     * @return string
     */
    protected function renderCartItems(array $items): string
    {
        $html = '<table style="width:100%; border-collapse:collapse;">';
        $html .= '<tr style="border-bottom:1px solid #ddd;">';
        $html .= '<th style="text-align:left; padding:8px;">Item</th>';
        $html .= '<th style="text-align:center; padding:8px;">Qty</th>';
        $html .= '<th style="text-align:right; padding:8px;">Price</th>';
        $html .= '</tr>';

        foreach ($items as $item) {
            $html .= '<tr style="border-bottom:1px solid #eee;">';
            $html .= '<td style="padding:8px;">' . e($item['title'] ?? 'Unknown Item') . '</td>';
            $html .= '<td style="text-align:center; padding:8px;">' . ($item['qty'] ?? 1) . '</td>';
            $html .= '<td style="text-align:right; padding:8px;">$' . number_format($item['price'] ?? 0, 2) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        return $html;
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        $queueItem = NewsletterAutomationQueue::find($this->queueItemId);
        if ($queueItem) {
            $queueItem->markAsFailed($exception->getMessage());
        }

        Log::error('ProcessTriggeredEmail: Job failed after all retries', [
            'queue_item_id' => $this->queueItemId,
            'error' => $exception->getMessage(),
        ]);
    }
}
