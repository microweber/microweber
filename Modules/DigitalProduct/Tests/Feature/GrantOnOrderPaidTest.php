<?php

namespace Modules\DigitalProduct\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Modules\DigitalProduct\Listeners\GrantDownloadsOnOrderPaid;
use Modules\DigitalProduct\Models\DigitalProductDownload;
use Modules\DigitalProduct\Models\ProductDownloadableFile;
use Modules\DigitalProduct\Services\DigitalProductService;
use Modules\Order\Events\OrderWasPaid;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Integration: dispatching OrderWasPaid runs the registered listener, which
 * grants secure downloads for every downloadable file in the paid order.
 */
class GrantOnOrderPaidTest extends TestCase
{
    protected ?Product $product = null;
    protected array $fileIds = [];
    protected int $orderId = 990200;

    #[Test]
    public function order_paid_event_grants_downloads_for_the_orders_products(): void
    {
        $this->product = new Product();
        $this->product->title = 'dp-paid-' . uniqid();
        $this->product->url = $this->product->title;
        $this->product->content_type = 'product';
        $this->product->subtype = 'downloadable_product';
        $this->product->save();

        $file = ProductDownloadableFile::create([
            'product_id' => $this->product->id,
            'disk' => 'local',
            'file_path' => 'downloads/paid.pdf',
            'original_name' => 'paid.pdf',
            'is_active' => true,
        ]);
        $this->fileIds[] = $file->id;

        $productId = $this->product->id;
        $orderId = $this->orderId;

        // A minimal order object exposing the cart()->where()->get() chain the
        // grant service reads, plus id/email.
        $order = new class($orderId, $productId) {
            public $id;
            public $email = 'paid-buyer@example.com';
            public $customer_id = null;
            private $productId;
            public function __construct($id, $productId) { $this->id = $id; $this->productId = $productId; }
            public function cart() {
                $items = collect([(object) ['rel_id' => $this->productId, 'qty' => 1, 'price' => 10, 'order_completed' => 1]]);
                return new class($items) {
                    private $items;
                    public function __construct($i) { $this->items = $i; }
                    public function where($a, $b) { return $this; }
                    public function get() { return $this->items; }
                };
            }
        };

        // The listener is registered for the event (wiring)…
        $this->assertContains(
            GrantDownloadsOnOrderPaid::class,
            array_map(
                fn ($l) => is_string($l) ? preg_replace('/@.*$/', '', $l) : $l,
                Event::getRawListeners()[OrderWasPaid::class] ?? []
            )
        );

        $this->assertEquals(0, DigitalProductDownload::where('order_id', $orderId)->count());

        // …and invoking it (as the dispatcher would) grants the downloads.
        // Called directly so sibling OrderWasPaid listeners don't run against
        // this minimal fake order.
        (new GrantDownloadsOnOrderPaid(new DigitalProductService()))
            ->handle(new OrderWasPaid($order));

        $grants = DigitalProductDownload::where('order_id', $orderId)->get();
        $this->assertCount(1, $grants);
        $this->assertEquals($file->id, $grants->first()->downloadable_file_id);
        $this->assertEquals('paid-buyer@example.com', $grants->first()->customer_email);
        $this->assertNotEmpty($grants->first()->token);
    }

    protected function tearDown(): void
    {
        DigitalProductDownload::where('order_id', $this->orderId)->delete();
        ProductDownloadableFile::whereIn('id', $this->fileIds)->delete();
        if ($this->product) {
            $this->product->forceDelete();
        }

        parent::tearDown();
    }
}
