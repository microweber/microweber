<?php

namespace Modules\DigitalProduct\Tests\Unit;

use Illuminate\Support\Facades\Storage;
use Modules\DigitalProduct\Models\DigitalProductDownload;
use Modules\DigitalProduct\Models\ProductDownloadableFile;
use Modules\DigitalProduct\Services\DigitalProductService;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

class DigitalProductDownloadTest extends TestCase
{
    protected ?Product $product = null;
    protected array $cleanupFileIds = [];

    protected function makeProduct(): Product
    {
        $product = new Product();
        $product->title = 'digital-test-product-' . uniqid();
        $product->url = 'digital-test-product-' . uniqid();
        $product->content_type = 'product';
        $product->subtype = 'product';
        $product->save();

        return $product;
    }

    protected function makeFileRecord(Product $product, string $disk, string $path, array $attrs = []): ProductDownloadableFile
    {
        $file = ProductDownloadableFile::create(array_merge([
            'product_id' => $product->id,
            'disk' => $disk,
            'file_path' => $path,
            'original_name' => 'ebook.pdf',
            'size' => 1234,
            'mime' => 'application/pdf',
            'is_active' => true,
        ], $attrs));

        $this->cleanupFileIds[] = $file->id;

        return $file;
    }

    #[Test]
    public function grant_download_rules_enforce_limit_and_expiry(): void
    {
        $unlimited = new DigitalProductDownload(['download_limit' => null, 'downloads_count' => 99]);
        $this->assertTrue($unlimited->canDownload(), 'null limit = unlimited');

        $capped = new DigitalProductDownload(['download_limit' => 2, 'downloads_count' => 2]);
        $this->assertTrue($capped->hasReachedLimit());
        $this->assertFalse($capped->canDownload());

        $under = new DigitalProductDownload(['download_limit' => 2, 'downloads_count' => 1]);
        $this->assertTrue($under->canDownload());

        $expired = new DigitalProductDownload(['expires_at' => now()->subDay()]);
        $this->assertTrue($expired->isExpired());
        $this->assertFalse($expired->canDownload());

        $never = new DigitalProductDownload(['expires_at' => null]);
        $this->assertFalse($never->isExpired());
        $this->assertTrue($never->canDownload());
    }

    #[Test]
    public function stream_download_delivers_the_file_and_counts_the_hit(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('downloads/ebook.pdf', 'PDF-BYTES');

        $this->product = $this->makeProduct();
        $file = $this->makeFileRecord($this->product, 'local', 'downloads/ebook.pdf');

        $grant = DigitalProductDownload::create([
            'order_id' => 999001,
            'product_id' => $this->product->id,
            'downloadable_file_id' => $file->id,
            'token' => DigitalProductDownload::generateToken(),
            'downloads_count' => 0,
            'download_limit' => null,
            'expires_at' => null,
        ]);

        $service = new DigitalProductService();
        $response = $service->streamDownload($grant->token);

        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertEquals(1, $grant->fresh()->downloads_count);
        $this->assertNotNull($grant->fresh()->last_downloaded_at);

        // Unknown token -> null
        $this->assertNull($service->streamDownload('nope-not-a-real-token'));
    }

    #[Test]
    public function stream_download_refuses_when_limit_reached(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('downloads/limited.pdf', 'X');

        $this->product = $this->makeProduct();
        $file = $this->makeFileRecord($this->product, 'local', 'downloads/limited.pdf');

        $grant = DigitalProductDownload::create([
            'order_id' => 999002,
            'product_id' => $this->product->id,
            'downloadable_file_id' => $file->id,
            'token' => DigitalProductDownload::generateToken(),
            'downloads_count' => 0,
            'download_limit' => 1,
            'expires_at' => null,
        ]);

        $service = new DigitalProductService();
        $this->assertInstanceOf(StreamedResponse::class, $service->streamDownload($grant->token));
        // second attempt is over the limit
        $this->assertNull($service->streamDownload($grant->token));
    }

    #[Test]
    public function grant_for_order_creates_one_grant_per_file_and_is_idempotent(): void
    {
        $this->product = $this->makeProduct();
        $file = $this->makeFileRecord($this->product, 'local', 'downloads/order.pdf');

        $productId = $this->product->id;
        $items = collect([(object) ['rel_id' => $productId, 'qty' => 1, 'order_completed' => 1]]);

        $order = new class($items) {
            public $id = 999003;
            public $email = 'buyer@example.com';
            public $customer_id = null;
            private $items;
            public function __construct($items) { $this->items = $items; }
            public function cart() {
                return new class($this->items) {
                    private $items;
                    public function __construct($i) { $this->items = $i; }
                    public function where($a, $b) { return $this; }
                    public function get() { return $this->items; }
                };
            }
        };

        $service = new DigitalProductService();

        $first = $service->grantForOrder($order);
        $this->assertCount(1, $first);
        $this->assertEquals('buyer@example.com', $first[0]->customer_email);
        $this->assertNotEmpty($first[0]->token);

        // Re-firing must not duplicate grants (firstOrCreate on order_id+file).
        $service->grantForOrder($order);
        $this->assertEquals(
            1,
            DigitalProductDownload::where('order_id', 999003)->where('downloadable_file_id', $file->id)->count()
        );
    }

    protected function tearDown(): void
    {
        DigitalProductDownload::whereIn('order_id', [999001, 999002, 999003])->delete();
        if (!empty($this->cleanupFileIds)) {
            DigitalProductDownload::whereIn('downloadable_file_id', $this->cleanupFileIds)->delete();
            ProductDownloadableFile::whereIn('id', $this->cleanupFileIds)->delete();
        }
        if ($this->product) {
            $this->product->forceDelete();
        }

        parent::tearDown();
    }
}
