<?php

namespace Modules\DigitalProduct\Tests\Feature;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Modules\Cart\Models\Cart;
use Modules\DigitalProduct\Mail\DownloadLinksMail;
use Modules\DigitalProduct\Models\DigitalProductDownload;
use Modules\DigitalProduct\Models\ProductDownloadableFile;
use Modules\DigitalProduct\Services\DigitalProductService;
use Modules\Order\Events\OrderWasPaid;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * The per-product "Email download link on order" opt-in: when a paid order
 * contains a product with the flag on, the buyer is emailed the SECURE
 * tokenized download link (never the file / a public URL). Off by default, and
 * delivery is idempotent across repeated paid/updated events.
 */
class EmailDownloadOnOrderTest extends TestCase
{
    protected ?Product $product = null;
    protected ?Order $order = null;
    protected array $fileIds = [];

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Mail::fake();
    }

    private function seedScenario(bool $emailFlag): array
    {
        $product = new Product(['subtype' => 'downloadable_product']);
        $product->title = 'ebook-mail-' . uniqid();
        $product->url = $product->title;
        $product->content_type = 'product';
        $product->save();
        // Per-product opt-in flag lives in content_data.
        $product->setContentData([DigitalProductService::EMAIL_FLAG => $emailFlag ? '1' : '0']);
        $product->save();
        $this->product = $product;

        $path = 'digital-products/' . uniqid() . '-ebook.pdf';
        Storage::disk('local')->put($path, 'PDF');
        $file = ProductDownloadableFile::create([
            'product_id' => $product->id,
            'disk' => 'local',
            'file_path' => $path,
            'original_name' => 'ebook.pdf',
            'size' => 3,
            'mime' => 'application/pdf',
            'is_active' => true,
        ]);
        $this->fileIds[] = $file->id;

        $order = Order::factory()->create([
            'email' => 'reader@example.com',
            'order_completed' => 1,
            'is_paid' => 1,
            'amount' => 9.99,
        ]);
        $this->order = $order;

        Cart::create([
            'rel_id' => $product->id,
            'rel_type' => morph_name(Product::class),
            'title' => $product->title,
            'qty' => 1,
            'price' => 9.99,
            'order_id' => $order->id,
            'order_completed' => 1,
            'session_id' => 'dptest-' . uniqid(),
        ]);

        return [$product, $file, $order];
    }

    #[Test]
    public function flag_on_emails_the_buyer_a_secure_download_link(): void
    {
        [$product, $file, $order] = $this->seedScenario(emailFlag: true);

        event(new OrderWasPaid($order));

        $grant = DigitalProductDownload::where('order_id', $order->id)->firstOrFail();
        $expectedUrl = route('digital-product.download', ['token' => $grant->token]);

        Mail::assertSent(DownloadLinksMail::class, function (DownloadLinksMail $mail) use ($order, $expectedUrl) {
            $urls = array_column($mail->links, 'url');

            return $mail->hasTo('reader@example.com')
                && (string) $mail->orderId === (string) $order->id
                && in_array($expectedUrl, $urls, true);
        });

        // The grant is stamped notified so it won't be emailed again.
        $this->assertNotNull($grant->fresh()->notified_at);
    }

    #[Test]
    public function flag_off_sends_no_email(): void
    {
        [, , $order] = $this->seedScenario(emailFlag: false);

        event(new OrderWasPaid($order));

        Mail::assertNotSent(DownloadLinksMail::class);
        $grant = DigitalProductDownload::where('order_id', $order->id)->firstOrFail();
        $this->assertNull($grant->fresh()->notified_at);
    }

    #[Test]
    public function email_is_sent_only_once_even_if_order_paid_fires_again(): void
    {
        [, , $order] = $this->seedScenario(emailFlag: true);

        event(new OrderWasPaid($order));
        event(new OrderWasPaid($order));

        Mail::assertSent(DownloadLinksMail::class, 1);
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
