<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use MicroweberPackages\ImageOptimization\Services\ImageOptimizationService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * HTTP endpoints for image optimization (serve WebP, stats, clear cache).
 *
 * Standalone — does not depend on the Microweber CMS.
 */
class ImageOptimizationController extends Controller
{
    public function __construct(
        protected ImageOptimizationService $service
    ) {
    }

    /**
     * GET /image-optimization/webp?src=...
     *
     * Convert source image to WebP (if needed) and serve the result.
     */
    public function serveWebp(Request $request): BinaryFileResponse|Response|JsonResponse
    {
        $src = (string) $request->query('src', '');
        if ($src === '') {
            return response()->json(['error' => 'Missing src parameter'], 422);
        }

        // Prevent path traversal
        if (str_contains($src, '..')) {
            return response()->json(['error' => 'Invalid src'], 400);
        }

        $options = [];
        if ($request->has('quality')) {
            $options['quality'] = (int) $request->query('quality');
        }
        if ($request->has('width')) {
            $options['width'] = (int) $request->query('width');
        }
        if ($request->has('height')) {
            $options['height'] = (int) $request->query('height');
        }

        // Force client WebP accept for conversion path when serving WebP endpoint
        $request->headers->set('Accept', 'image/webp,' . $request->header('Accept', ''));

        $result = $this->service->convertToWebp($src, $options);

        if ($result === null || empty($result['full_path']) || !is_file((string) $result['full_path'])) {
            // Fall back to original file if present
            $full = $this->service->resolveFullPath($src);
            if (is_file($full)) {
                $mime = mime_content_type($full) ?: 'application/octet-stream';

                return response()->file($full, [
                    'Content-Type' => $mime,
                    'Cache-Control' => 'public, max-age=86400',
                ]);
            }

            return response()->json(['error' => 'Unable to convert or find image'], 404);
        }

        return response()->file((string) $result['full_path'], [
            'Content-Type' => 'image/webp',
            'Cache-Control' => 'public, max-age=604800',
            'X-Image-Optimization' => 'webp',
        ]);
    }

    /**
     * GET /image-optimization/stats
     */
    public function stats(): JsonResponse
    {
        return response()->json($this->service->getStatistics());
    }

    /**
     * POST /image-optimization/clear-cache
     */
    public function clearCache(): JsonResponse
    {
        if (function_exists('is_admin') && !is_admin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $count = $this->service->clearWebpCache();

        return response()->json([
            'success' => true,
            'deleted' => $count,
        ]);
    }

    /**
     * GET /api/image-optimization/convert?src=...
     *
     * Returns JSON metadata about conversion (does not stream the file).
     */
    public function convert(Request $request): JsonResponse
    {
        $src = (string) $request->query('src', '');
        if ($src === '') {
            return response()->json(['error' => 'Missing src parameter'], 422);
        }

        if (str_contains($src, '..')) {
            return response()->json(['error' => 'Invalid src'], 400);
        }

        $options = [];
        if ($request->has('quality')) {
            $options['quality'] = (int) $request->query('quality');
        }
        if ($request->has('width')) {
            $options['width'] = (int) $request->query('width');
        }
        if ($request->has('height')) {
            $options['height'] = (int) $request->query('height');
        }

        $result = $this->service->convertToWebp($src, $options);

        if ($result === null) {
            return response()->json([
                'success' => false,
                'error' => 'Conversion failed or WebP not supported',
                'src' => $src,
                'webp_supported' => $this->service->isWebpSupported(),
                'webp_enabled' => $this->service->isWebpEnabled(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}
