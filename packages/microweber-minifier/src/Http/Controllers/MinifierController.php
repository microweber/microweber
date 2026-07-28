<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MicroweberPackages\Minifier\Services\MinifierService;

/**
 * HTTP endpoints for minification (stats, minify, self-test).
 *
 * Standalone — does not depend on the Microweber CMS.
 */
class MinifierController extends Controller
{
    public function __construct(
        protected MinifierService $service
    ) {
    }

    /**
     * GET /minifier/stats
     */
    public function stats(): JsonResponse
    {
        return response()->json($this->service->getStatistics());
    }

    /**
     * GET /minifier/self-test
     */
    public function selfTest(): JsonResponse
    {
        return response()->json($this->service->selfTest());
    }

    /**
     * POST /minifier/js  body: { "code": "..." } or form field "code"
     * Also accepts raw body as JS when Content-Type is application/javascript.
     */
    public function minifyJs(Request $request): JsonResponse
    {
        if (!$this->service->isJsEnabled()) {
            return response()->json(['error' => 'JS minification is disabled'], 503);
        }

        $code = $this->extractCode($request);
        if ($code === null) {
            return response()->json(['error' => 'Missing code parameter'], 422);
        }

        $minified = $this->service->minifyJs($code);

        return response()->json([
            'success' => true,
            'original_length' => strlen($code),
            'minified_length' => strlen($minified),
            'code' => $minified,
        ]);
    }

    /**
     * POST /minifier/css
     */
    public function minifyCss(Request $request): JsonResponse
    {
        if (!$this->service->isCssEnabled()) {
            return response()->json(['error' => 'CSS minification is disabled'], 503);
        }

        $code = $this->extractCode($request);
        if ($code === null) {
            return response()->json(['error' => 'Missing code parameter'], 422);
        }

        $minified = $this->service->minifyCss($code);

        return response()->json([
            'success' => true,
            'original_length' => strlen($code),
            'minified_length' => strlen($minified),
            'code' => $minified,
        ]);
    }

    /**
     * GET /api/minifier/ping — lightweight health check
     */
    public function ping(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'package' => 'microweber-packages/minifier',
            'enabled' => $this->service->isEnabled(),
        ]);
    }

    protected function extractCode(Request $request): ?string
    {
        $code = $request->input('code');
        if (is_string($code) && $code !== '') {
            return $code;
        }

        $contentType = (string) $request->header('Content-Type', '');
        if (str_contains($contentType, 'javascript') || str_contains($contentType, 'css') || str_contains($contentType, 'text/plain')) {
            $raw = $request->getContent();
            if ($raw !== '') {
                return $raw;
            }
        }

        return null;
    }
}
