<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use MicroweberPackages\Queue\Services\QueueProcessor;

/**
 * HTTP entry-point for processing pending jobs (cron / admin trigger).
 */
class ProcessQueueController extends Controller
{
    public function __construct(
        protected QueueProcessor $processor,
    ) {
    }

    public function handle(): JsonResponse|int
    {
        $processed = $this->processor->process();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'processed' => $processed,
            ]);
        }

        return $processed;
    }
}
