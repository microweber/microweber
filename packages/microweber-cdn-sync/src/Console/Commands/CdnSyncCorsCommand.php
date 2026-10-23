<?php

namespace MicroweberPackages\CdnSync\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\CdnSync\Services\CdnSyncService;

/**
 * Set (or show) the object-storage bucket's CORS policy.
 *
 * The browser blocks cross-origin reads of the template's live_edit.css (and any
 * other userfile loaded from the CDN bucket) unless the bucket returns CORS
 * headers. Hetzner Object Storage is S3-compatible, so we set the policy via the
 * S3 API. One bucket-level policy (GET/HEAD from any origin) covers EVERY domain
 * in a multisite setup — run this once per bucket, not per domain.
 *
 * @see https://docs.hetzner.com/storage/object-storage/howto-protect-objects/cors/
 */
class CdnSyncCorsCommand extends Command
{
    protected $signature = 'cdn-sync:cors
        {--show : Print the bucket\'s current CORS configuration without changing it}';

    protected $description = 'Set the CDN bucket CORS policy so every site domain can load userfiles (CSS/fonts/media) cross-origin';

    public function handle(CdnSyncService $service): int
    {
        if (!$service->isConfigured()) {
            $this->error('CDN sync is not configured — set CDN_SYNC_KEY, CDN_SYNC_SECRET and CDN_SYNC_BUCKET first.');

            return self::FAILURE;
        }

        $bucket = $service->getConfigValue('bucket');

        try {
            if ($this->option('show')) {
                $rules = $service->getCurrentCors();

                if (empty($rules)) {
                    $this->warn("Bucket '{$bucket}' has no CORS configuration set.");
                } else {
                    $this->info("Current CORS rules on bucket '{$bucket}':");
                    $this->line(json_encode($rules, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                }

                return self::SUCCESS;
            }

            $this->info("Applying CORS policy to bucket '{$bucket}' (GET/HEAD from any origin)…");
            $service->applyCors();
            $this->info('Done. All multisite domains (and dev/staging) can now load userfiles cross-origin — one policy covers every domain, no per-domain setup needed.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Failed to update bucket CORS: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
