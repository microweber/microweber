<?php

namespace Modules\Product\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Content\Models\Content;
use Modules\Product\Models\ProductInventoryAlert;
use Modules\Product\Models\ProductVariantCombination;

/**
 * Low Stock Notification
 *
 * Sent when a product or variant reaches low stock threshold.
 */
class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The inventory alert.
     *
     * @var ProductInventoryAlert
     */
    public $alert;

    /**
     * The product.
     *
     * @var Content|null
     */
    public $product;

    /**
     * The variant (if applicable).
     *
     * @var ProductVariantCombination|null
     */
    public $variant;

    /**
     * Create a new notification instance.
     */
    public function __construct(ProductInventoryAlert $alert)
    {
        $this->alert = $alert;
        $this->product = $alert->product;
        $this->variant = $alert->variant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->getSubject();
        $line1 = $this->getNotificationLine1();
        $line2 = $this->getNotificationLine2();

        return (new MailMessage)
            ->subject($subject)
            ->line($line1)
            ->line($line2)
            ->action('View Inventory', url('/admin/inventory'))
            ->line('Please restock this item as soon as possible.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'alert_id' => $this->alert->id,
            'alert_type' => $this->alert->alert_type,
            'product_id' => $this->alert->product_id,
            'variant_id' => $this->alert->variant_id,
            'product_title' => $this->product?->title ?? 'Unknown Product',
            'variant_sku' => $this->variant?->sku,
            'current_quantity' => $this->alert->current_quantity,
            'threshold_quantity' => $this->alert->threshold_quantity,
            'severity' => $this->alert->severity,
        ];
    }

    /**
     * Get the notification subject.
     */
    protected function getSubject(): string
    {
        $productName = $this->product?->title ?? 'Unknown Product';

        return match ($this->alert->alert_type) {
            ProductInventoryAlert::TYPE_OUT_OF_STOCK => "⚠️ OUT OF STOCK: {$productName}",
            ProductInventoryAlert::TYPE_CRITICAL => "🔴 CRITICAL: {$productName} Low Stock",
            ProductInventoryAlert::TYPE_LOW_STOCK => "⚡ Low Stock Alert: {$productName}",
            default => "📦 Inventory Alert: {$productName}",
        };
    }

    /**
     * Get the first notification line.
     */
    protected function getNotificationLine1(): string
    {
        $productName = $this->product?->title ?? 'Unknown Product';

        if ($this->variant) {
            $variantInfo = $this->getVariantDisplayName();
            $productName .= " ({$variantInfo})";
        }

        return match ($this->alert->alert_type) {
            ProductInventoryAlert::TYPE_OUT_OF_STOCK => "The product '{$productName}' is now OUT OF STOCK.",
            ProductInventoryAlert::TYPE_CRITICAL => "The product '{$productName}' has reached CRITICAL stock levels.",
            ProductInventoryAlert::TYPE_LOW_STOCK => "The product '{$productName}' is running low on stock.",
            default => "Inventory alert for '{$productName}'.",
        };
    }

    /**
     * Get the second notification line.
     */
    protected function getNotificationLine2(): string
    {
        $current = $this->alert->current_quantity;
        $threshold = $this->alert->threshold_quantity;

        return "Current quantity: {$current} (Threshold: {$threshold})";
    }

    /**
     * Get variant display name.
     */
    protected function getVariantDisplayName(): string
    {
        if (!$this->variant) {
            return '';
        }

        $attributes = [];
        foreach ($this->variant->attributeValues as $value) {
            $attributes[] = $value->value;
        }

        return implode(', ', $attributes);
    }
}
