<?php

namespace Modules\DigitalProduct\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\DigitalProduct\Models\DigitalProductDownload;
use Modules\DigitalProduct\Models\ProductDownloadableFile;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Core digital-products logic: grant download rights for a paid order and stream
 * a granted file securely. Defaults: unlimited downloads, never expires — both
 * are per-file overridable (see ProductDownloadableFile::download_limit).
 */
class DigitalProductService
{
    /**
     * Create download grants for every downloadable file in a (paid) order.
     * Idempotent: an existing (order_id, downloadable_file_id) grant is reused,
     * so re-firing OrderWasPaid does not duplicate grants.
     *
     * When $freeOnly is true, only free line items (price <= 0) are granted —
     * used on OrderWasCreated so free digital products are available immediately
     * without a payment step, while paid items wait for OrderWasPaid.
     *
     * @return DigitalProductDownload[] the grants that now exist for this order
     */
    public function grantForOrder($order, bool $freeOnly = false): array
    {
        $grants = [];

        if (!$order || empty($order->id)) {
            return $grants;
        }

        try {
            $items = $order->cart()->where('order_completed', 1)->get();
        } catch (\Throwable $e) {
            Log::warning('DigitalProduct: could not read order cart', [
                'order_id' => $order->id,
                'exception' => $e->getMessage(),
            ]);

            return $grants;
        }

        $customerEmail = data_get($order, 'email');
        $customerId = data_get($order, 'customer_id');

        foreach ($items as $item) {
            $productId = $item->rel_id ?? null;
            if (!$productId) {
                continue;
            }

            // In free-only mode skip paid line items — they are granted when the
            // order is paid (OrderWasPaid), not at creation time.
            if ($freeOnly && (float) ($item->price ?? 0) > 0) {
                continue;
            }

            $files = ProductDownloadableFile::active()->where('product_id', $productId)->get();

            foreach ($files as $file) {
                $grant = DigitalProductDownload::firstOrCreate(
                    [
                        'order_id' => $order->id,
                        'downloadable_file_id' => $file->id,
                    ],
                    [
                        'product_id' => $productId,
                        'customer_id' => $customerId,
                        'customer_email' => $customerEmail,
                        'token' => DigitalProductDownload::generateToken(),
                        'downloads_count' => 0,
                        'download_limit' => $file->download_limit, // NULL = unlimited
                        'expires_at' => null,                      // NULL = never
                    ]
                );

                $grants[] = $grant;
            }
        }

        if (!empty($grants)) {
            Log::info('DigitalProduct: granted downloads for order', [
                'order_id' => $order->id,
                'grants' => count($grants),
            ]);
        }

        return $grants;
    }

    /**
     * Resolve a grant by its token, enforce limit/expiry, record the hit, and
     * return a streamed file response. Returns null when the token is unknown or
     * the grant can no longer be downloaded (caller maps that to 403/404).
     */
    public function streamDownload(string $token): ?StreamedResponse
    {
        $grant = DigitalProductDownload::where('token', $token)->first();

        if (!$grant || !$grant->canDownload()) {
            return null;
        }

        $file = $grant->file;
        if (!$file) {
            return null;
        }

        $disk = $file->disk ?: $this->defaultDisk();

        if (!Storage::disk($disk)->exists($file->file_path)) {
            Log::error('DigitalProduct: granted file missing on disk', [
                'grant_id' => $grant->id,
                'disk' => $disk,
                'path' => $file->file_path,
            ]);

            return null;
        }

        $grant->increment('downloads_count');
        $grant->forceFill(['last_downloaded_at' => now()])->save();

        return Storage::disk($disk)->download($file->file_path, $file->original_name);
    }

    /**
     * The filesystem disk downloadable files default to when none is stored —
     * the S3-aware userfiles disk when available, else Laravel's default.
     */
    public function defaultDisk(): string
    {
        if (function_exists('userfiles_disk')) {
            $disk = userfiles_disk();
            if (is_string($disk) && $disk !== '') {
                return $disk;
            }
        }

        return (string) config('filesystems.default', 'local');
    }
}
