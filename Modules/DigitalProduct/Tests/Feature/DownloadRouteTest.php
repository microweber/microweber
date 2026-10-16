<?php

namespace Modules\DigitalProduct\Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Modules\DigitalProduct\Models\DigitalProductDownload;
use Modules\DigitalProduct\Models\ProductDownloadableFile;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Exercises the public secure-delivery route GET /download/{token} end to end:
 * a valid grant streams the file and counts the hit; unknown, expired, and
 * over-limit tokens all 404 (no grant-state leak).
 */
class DownloadRouteTest extends TestCase
{
    protected ?Product $product = null;
    protected array $grantIds = [];
    protected array $fileIds = [];

    protected function makeFileWithGrant(array $grantAttrs = []): DigitalProductDownload
    {
        Storage::fake('local');
        Storage::disk('local')->put('downloads/route.pdf', 'PDF-BYTES');

        $this->product = new Product();
        $this->product->title = 'dp-route-' . uniqid();
        $this->product->url = $this->product->title;
        $this->product->content_type = 'product';
        $this->product->subtype = 'downloadable_product';
        $this->product->save();

        $file = ProductDownloadableFile::create([
            'product_id' => $this->product->id,
            'disk' => 'local',
            'file_path' => 'downloads/route.pdf',
            'original_name' => 'route.pdf',
            'is_active' => true,
        ]);
        $this->fileIds[] = $file->id;

        $grant = DigitalProductDownload::create(array_merge([
            'order_id' => 990100,
            'product_id' => $this->product->id,
            'downloadable_file_id' => $file->id,
            'token' => DigitalProductDownload::generateToken(),
            'downloads_count' => 0,
        ], $grantAttrs));
        $this->grantIds[] = $grant->id;

        return $grant;
    }

    #[Test]
    public function valid_token_downloads_the_file_and_counts_the_hit(): void
    {
        $grant = $this->makeFileWithGrant();

        $response = $this->get(route('digital-product.download', ['token' => $grant->token]));

        $response->assertOk();
        $this->assertEquals(1, $grant->fresh()->downloads_count);
    }

    #[Test]
    public function unknown_token_returns_404(): void
    {
        $this->makeFileWithGrant(); // ensure routes/tables exist
        $this->get(route('digital-product.download', ['token' => 'totally-unknown-token']))
            ->assertNotFound();
    }

    #[Test]
    public function expired_grant_returns_404(): void
    {
        $grant = $this->makeFileWithGrant(['expires_at' => now()->subDay()]);
        $this->get(route('digital-product.download', ['token' => $grant->token]))
            ->assertNotFound();
        $this->assertEquals(0, $grant->fresh()->downloads_count);
    }

    #[Test]
    public function over_limit_grant_returns_404(): void
    {
        $grant = $this->makeFileWithGrant(['download_limit' => 1, 'downloads_count' => 1]);
        $this->get(route('digital-product.download', ['token' => $grant->token]))
            ->assertNotFound();
    }

    protected function tearDown(): void
    {
        DigitalProductDownload::whereIn('id', $this->grantIds)->delete();
        ProductDownloadableFile::whereIn('id', $this->fileIds)->delete();
        if ($this->product) {
            $this->product->forceDelete();
        }

        parent::tearDown();
    }
}
