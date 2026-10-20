<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Modules\Cart\Models\Cart;
use Modules\DigitalProduct\Models\DigitalProductDownload;
use Modules\DigitalProduct\Models\ProductDownloadableFile;
use Modules\DigitalProduct\Services\DigitalProductService;
use Modules\Order\Events\OrderWasPaid;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\DuskTestCase;

/**
 * End-to-end ebook-sale scenario on the Base template, driven in a real browser.
 *
 * A downloadable ebook product (with the per-product "email download link on
 * order" opt-in) is purchased in a paid order; the registered OrderWasPaid
 * listeners grant the buyer a secure tokenized download and email the link.
 * This test then proves the two browser-level halves of the sale that need a
 * real browser on the Base template:
 *   1. the BUYER downloads their ebook through the secure /download/{token}
 *      link (the delivered file streams, and the hit is counted), and
 *   2. the purchase surfaces in the customer "My Downloads" account page.
 *
 * The purchase itself is placed through the real Order + cart rows and the real
 * OrderWasPaid event (the same pipeline the checkout runs); the storefront
 * checkout wizard has its own coverage and is intentionally not re-driven here.
 *
 * Prerequisites: dev server on http://127.0.0.1:8000, admin admin@admin.com/admin.
 */
class DigitalProductEbookSaleTest extends DuskTestCase
{
    use AdminLoginTrait;

    private const BUYER_EMAIL = 'admin@admin.com'; // matches the admin login so "My Downloads" shows it

    protected ?Product $product = null;
    protected ?Order $order = null;
    protected ?ProductDownloadableFile $file = null;
    protected ?DigitalProductDownload $grant = null;
    protected string $filePath = '';
    protected string $fileBytes = '';

    protected function assertPreConditions(): void
    {
        // Rely on the already-running dev server + shared testing DB.
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Base template active for this scenario.
        DB::table('options')->updateOrInsert(
            ['option_key' => 'current_template', 'option_group' => 'template'],
            ['option_value' => 'Base', 'updated_at' => now()]
        );
        try {
            app()->cache_manager->delete('options');
        } catch (\Throwable) {
        }

        // A downloadable ebook product that opts in to emailing its link on order.
        $this->product = new Product(['subtype' => 'downloadable_product']);
        $this->product->title = 'Ebook Sale ' . uniqid();
        $this->product->url = 'ebook-sale-' . uniqid();
        $this->product->content_type = 'product';
        $this->product->save();
        $this->product->setContentData([DigitalProductService::EMAIL_FLAG => '1']);
        $this->product->save();

        // The actual file, on the local 'public' disk so the running server can
        // stream it without S3.
        $this->fileBytes = 'EBOOK-PDF-BYTES-' . uniqid();
        $this->filePath = 'digital-products/ebook-' . uniqid() . '.pdf';
        Storage::disk('public')->put($this->filePath, $this->fileBytes);

        $this->file = ProductDownloadableFile::create([
            'product_id' => $this->product->id,
            'disk' => 'public',
            'file_path' => $this->filePath,
            'original_name' => 'my-ebook.pdf',
            'size' => strlen($this->fileBytes),
            'mime' => 'application/pdf',
            'is_active' => true,
        ]);

        // A paid order for the buyer, with the product as a real cart line item.
        $this->order = Order::factory()->create([
            'email' => self::BUYER_EMAIL,
            'order_completed' => 1,
            'is_paid' => 1,
            'amount' => 7.50,
        ]);
        Cart::create([
            'rel_id' => $this->product->id,
            'rel_type' => morph_name(Product::class),
            'title' => $this->product->title,
            'qty' => 1,
            'price' => 7.50,
            'order_id' => $this->order->id,
            'order_completed' => 1,
            'session_id' => 'ebook-' . uniqid(),
        ]);

        // Use the in-memory mail transport in this test process so the
        // email-on-order path runs deterministically without a real MTA
        // (the box has no sendmail); we assert delivery via the grant's
        // notified_at stamp set by the listener.
        config(['mail.default' => 'array']);

        // The real purchase pipeline: grant the secure download + email the link.
        event(new OrderWasPaid($this->order));

        $this->grant = DigitalProductDownload::where('order_id', $this->order->id)->first();
    }

    #[Test]
    public function buyer_downloads_their_ebook_via_the_secure_link_in_the_browser(): void
    {
        $this->assertNotNull($this->grant, 'The paid order should have granted a secure download');
        $token = $this->grant->token;
        $downloadUrl = '/download/' . $token;

        $this->browse(function (Browser $browser) use ($downloadUrl) {
            // Land on the Base-template site first (same origin as the download).
            $browser->visit('/')->pause(1500);

            // Fetch the secure download exactly as the browser would, from the
            // real site origin, and read back status + the delivered bytes.
            $result = $browser->script(<<<JS
                try {
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', '{$downloadUrl}', false);
                    xhr.send(null);
                    return xhr.status + '||' + (xhr.responseText || '').slice(0, 80);
                } catch (e) { return 'ERR:' + e.message; }
            JS)[0] ?? '';

            [$status, $body] = array_pad(explode('||', (string) $result, 2), 2, '');

            $this->assertSame('200', trim($status),
                'The secure download link must deliver the file (got: ' . $result . ')');
            $this->assertStringContainsString($this->fileBytes, (string) $body,
                'The browser must receive the actual ebook file bytes over the secure link');
        });

        // The download was recorded against the grant.
        $this->assertSame(1, (int) $this->grant->fresh()->downloads_count,
            'Downloading via the secure link must count the hit');

        // The opt-in product emailed the link (notified_at stamped by the listener).
        $this->assertNotNull($this->grant->fresh()->notified_at,
            'The per-product email-on-order opt-in should have emailed the download link');
    }

    #[Test]
    public function purchase_appears_in_the_customer_my_downloads_page(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            // "My Downloads" lives in the customer profile panel.
            $browser->visit('/profile/my-downloads')->pause(3500);

            $browser->assertSee($this->product->title);
            $browser->assertSee('my-ebook.pdf');
            $browser->assertSee('Available');
        });
    }

    protected function tearDown(): void
    {
        try {
            if ($this->order) {
                DigitalProductDownload::where('order_id', $this->order->id)->delete();
                Cart::where('order_id', $this->order->id)->forceDelete();
                $this->order->forceDelete();
            }
            if ($this->file) {
                $this->file->delete();
            }
            if ($this->filePath) {
                Storage::disk('public')->delete($this->filePath);
            }
            if ($this->product) {
                $this->product->forceDelete();
            }
        } catch (\Throwable) {
            // best-effort cleanup
        }

        parent::tearDown();
    }
}
