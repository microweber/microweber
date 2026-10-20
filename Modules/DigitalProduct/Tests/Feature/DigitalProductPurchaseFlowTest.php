<?php

namespace Modules\DigitalProduct\Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Modules\Cart\Models\Cart;
use Modules\DigitalProduct\Models\DigitalProductDownload;
use Modules\DigitalProduct\Models\ProductDownloadableFile;
use Modules\Order\Events\OrderWasPaid;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * True end-to-end purchase flow (no mock order objects): a real downloadable
 * Product with a real file, a real Order with real `cart` line items, the real
 * OrderWasPaid event running the registered listener, and the real
 * /download/{token} route streaming the granted file. This is the integration
 * the existing (mock-based) feature tests do not cover.
 */
class DigitalProductPurchaseFlowTest extends TestCase
{
    protected ?Product $product = null;
    protected ?Order $order = null;
    protected array $fileIds = [];

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    private function makeDownloadableProduct(float $price = 12.50): Product
    {
        $p = new Product(['subtype' => 'downloadable_product']);
        $p->title = 'ebook-' . uniqid();
        $p->url = $p->title;
        $p->content_type = 'product';
        $p->save();
        $this->product = $p;

        return $p;
    }

    private function attachFile(Product $p, string $name = 'ebook.pdf'): ProductDownloadableFile
    {
        $path = 'digital-products/' . uniqid() . '-' . $name;
        Storage::disk('local')->put($path, 'PDF-BYTES-' . $name);

        $file = ProductDownloadableFile::create([
            'product_id' => $p->id,
            'disk' => 'local',
            'file_path' => $path,
            'original_name' => $name,
            'size' => 9,
            'mime' => 'application/pdf',
            'is_active' => true,
        ]);
        $this->fileIds[] = $file->id;

        return $file;
    }

    private function makePaidOrderWithProduct(Product $p, float $price = 12.50, string $email = 'buyer@example.com'): Order
    {
        $order = Order::factory()->create([
            'email' => $email,
            'order_completed' => 1,
            'is_paid' => 1,
            'amount' => $price,
        ]);
        $this->order = $order;

        Cart::create([
            'rel_id' => $p->id,
            'rel_type' => morph_name(Product::class),
            'title' => $p->title,
            'qty' => 1,
            'price' => $price,
            'order_id' => $order->id,
            'order_completed' => 1,
            // Real cart items always carry a session_id; other OrderWasPaid
            // listeners (e.g. inventory reservation release) require it.
            'session_id' => 'dptest-' . uniqid(),
        ]);

        return $order;
    }

    #[Test]
    public function paid_order_grants_a_secure_download_that_actually_streams_the_file(): void
    {
        $product = $this->makeDownloadableProduct();
        $file = $this->attachFile($product);
        $order = $this->makePaidOrderWithProduct($product);

        // Real event → real registered listener → grantForOrder().
        event(new OrderWasPaid($order));

        $grant = DigitalProductDownload::where('order_id', $order->id)
            ->where('downloadable_file_id', $file->id)
            ->first();

        $this->assertNotNull($grant, 'A download grant must be created for the paid order');
        $this->assertSame('buyer@example.com', $grant->customer_email);
        $this->assertNotEmpty($grant->token);
        $this->assertSame(0, $grant->downloads_count);

        // The real tokenized route delivers the file and counts the hit.
        $response = $this->get(route('digital-product.download', ['token' => $grant->token]));
        $response->assertOk();
        $this->assertStringContainsString('PDF-BYTES-ebook.pdf', $response->streamedContent());

        $this->assertSame(1, (int) $grant->fresh()->downloads_count);
    }

    #[Test]
    public function re_firing_order_paid_does_not_duplicate_grants(): void
    {
        $product = $this->makeDownloadableProduct();
        $file = $this->attachFile($product);
        $order = $this->makePaidOrderWithProduct($product);

        event(new OrderWasPaid($order));
        event(new OrderWasPaid($order));

        $count = DigitalProductDownload::where('order_id', $order->id)
            ->where('downloadable_file_id', $file->id)
            ->count();

        $this->assertSame(1, $count, 'Grants must be idempotent across repeated OrderWasPaid');
    }

    #[Test]
    public function download_route_returns_404_once_the_limit_is_reached(): void
    {
        $product = $this->makeDownloadableProduct();
        $file = $this->attachFile($product);
        // Per-file limit of 1 download.
        $file->update(['download_limit' => 1]);
        $order = $this->makePaidOrderWithProduct($product);

        event(new OrderWasPaid($order));
        $grant = DigitalProductDownload::where('order_id', $order->id)->firstOrFail();
        $this->assertSame(1, (int) $grant->download_limit);

        // First download OK, second over-limit → 404 (no state leak).
        $this->get(route('digital-product.download', ['token' => $grant->token]))->assertOk();
        $this->get(route('digital-product.download', ['token' => $grant->token]))->assertNotFound();
    }

    #[Test]
    public function unknown_token_returns_404(): void
    {
        $this->get(route('digital-product.download', ['token' => 'definitely-not-a-real-token']))
            ->assertNotFound();
    }

    protected function tearDown(): void
    {
        DigitalProductDownload::query()
            ->when($this->order, fn ($q) => $q->where('order_id', $this->order->id))
            ->delete();
        if (!empty($this->fileIds)) {
            ProductDownloadableFile::whereIn('id', $this->fileIds)->delete();
        }
        if ($this->order) {
            Cart::where('order_id', $this->order->id)->forceDelete();
            $this->order->forceDelete();
        }
        if ($this->product) {
            $this->product->forceDelete();
        }

        parent::tearDown();
    }
}
