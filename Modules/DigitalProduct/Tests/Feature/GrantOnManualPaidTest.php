<?php

namespace Modules\DigitalProduct\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Modules\DigitalProduct\Listeners\GrantDownloadsOnOrderUpdated;
use Modules\DigitalProduct\Models\DigitalProductDownload;
use Modules\DigitalProduct\Models\ProductDownloadableFile;
use Modules\DigitalProduct\Services\DigitalProductService;
use Modules\Order\Events\OrderWasUpdated;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Marking an order paid manually in the admin only fires OrderWasUpdated (not
 * OrderWasPaid). GrantDownloadsOnOrderUpdated must then grant the downloads so
 * the digital products work — and must be idempotent and not grant for unpaid
 * updates.
 */
class GrantOnManualPaidTest extends TestCase
{
    protected ?Product $product = null;
    protected array $fileIds = [];
    protected int $orderId = 990300;

    protected function fakeOrder(bool $isPaid): object
    {
        $productId = $this->product->id;
        $orderId = $this->orderId;

        return new class($orderId, $productId, $isPaid) {
            public $id;
            public $is_paid;
            public $email = 'manual-buyer@example.com';
            public $customer_id = null;
            private $productId;
            public function __construct($id, $productId, $isPaid) { $this->id = $id; $this->productId = $productId; $this->is_paid = $isPaid; }
            public function getModel() { return $this; }
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
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = new Product();
        $this->product->title = 'dp-manual-' . uniqid();
        $this->product->url = $this->product->title;
        $this->product->content_type = 'product';
        $this->product->subtype = 'downloadable_product';
        $this->product->save();

        $file = ProductDownloadableFile::create([
            'product_id' => $this->product->id,
            'disk' => 'local',
            'file_path' => 'downloads/manual.pdf',
            'original_name' => 'manual.pdf',
            'is_active' => true,
        ]);
        $this->fileIds[] = $file->id;
    }

    #[Test]
    public function listener_is_registered_for_order_updated(): void
    {
        $this->assertContains(
            GrantDownloadsOnOrderUpdated::class,
            array_map(
                fn ($l) => is_string($l) ? preg_replace('/@.*$/', '', $l) : $l,
                Event::getRawListeners()[OrderWasUpdated::class] ?? []
            )
        );
    }

    #[Test]
    public function marking_paid_manually_grants_downloads_idempotently(): void
    {
        $listener = new GrantDownloadsOnOrderUpdated(new DigitalProductService());

        // Unpaid update → no grant.
        $listener->handle(new OrderWasUpdated($this->fakeOrder(false)));
        $this->assertEquals(0, DigitalProductDownload::where('order_id', $this->orderId)->count());

        // Paid update (admin toggled is_paid) → grant created.
        $listener->handle(new OrderWasUpdated($this->fakeOrder(true)));
        $this->assertEquals(1, DigitalProductDownload::where('order_id', $this->orderId)->count());

        // A subsequent paid update must not duplicate the grant.
        $listener->handle(new OrderWasUpdated($this->fakeOrder(true)));
        $this->assertEquals(1, DigitalProductDownload::where('order_id', $this->orderId)->count());
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
