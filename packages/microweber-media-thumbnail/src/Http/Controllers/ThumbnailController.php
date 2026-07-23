<?php

namespace MicroweberPackages\MediaThumbnail\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use MicroweberPackages\MediaPixum\PixumGenerator;
use MicroweberPackages\MediaThumbnail\Repositories\MediaThumbnailRepository;
use MicroweberPackages\Thumbnailer\ThumbnailGenerator;

/**
 * Serves generated thumbnails and placeholder (pixum) images.
 *
 * Standalone — does not depend on the Microweber CMS.
 */
class ThumbnailController extends Controller
{
    public function __construct(
        protected ThumbnailGenerator $generator,
        protected MediaThumbnailRepository $repository,
        protected PixumGenerator $pixumGenerator
    ) {
    }

    /**
     * GET /thumbnail_img — generate & serve a thumbnail from query params.
     */
    public function thumbnailImg(Request $request): Response
    {
        /** @var array<string, mixed> $params */
        $params = $request->all();

        $path = $this->generateFromParams($params);

        if ($path === null || !is_file($path)) {
            return $this->servePixum($request);
        }

        return $this->serveFile($path);
    }

    /**
     * GET /pixum_img — serve a placeholder image.
     */
    public function pixumImg(Request $request): Response
    {
        return $this->servePixum($request);
    }

    /**
     * GET /api/media-thumbnail/generate/{uuid}
     *
     * Look up cached image_options by UUID, generate the thumbnail,
     * and serve it.
     */
    public function generateByUuid(string $uuid): Response
    {
        $uuid = str_replace('..', '', $uuid);

        $record = $this->repository->findByUuid($uuid);

        if (!$record) {
            return $this->servePixumDefault();
        }

        $opts = $record->image_options;
        if (is_array($opts)) {
            $opts['cache_id'] = $record->rel_id ?? $uuid;
        }

        $path = $this->generateFromParams($opts ?? []);

        if ($path === null || !is_file($path)) {
            return $this->servePixumDefault();
        }

        return $this->serveFile($path);
    }

    /**
     * Generate a thumbnail from a params array using ThumbnailGenerator.
     *
     * @param array<string, mixed> $params
     */
    protected function generateFromParams(array $params): ?string
    {
        $src = $params['src'] ?? null;

        if (!$src || !is_string($src)) {
            return null;
        }

        // Resolve site URL placeholders if they exist
        if (function_exists('site_url')) {
            /** @var string $siteUrl */
            $siteUrl = site_url();
            $src = str_replace('{SITE_URL}', $siteUrl, $src);
            $src = str_replace('%7BSITE_URL%7D', $siteUrl, $src);
        }

        // Try to resolve the source path
        $srcPath = $this->resolveSourcePath($src);

        if ($srcPath === null) {
            return null;
        }

        $rawWidth  = $params['width'] ?? 200;
        $rawHeight = $params['height'] ?? null;
        $width     = is_numeric($rawWidth) ? (int) $rawWidth : 200;
        $height    = is_numeric($rawHeight) ? (int) $rawHeight : null;
        $rawCrop   = $params['crop'] ?? null;
        $crop      = is_bool($rawCrop) || is_string($rawCrop) ? $rawCrop : null;

        return $this->generator->generate($srcPath, $width, $height, $crop);
    }

    /**
     * Resolve a URL or relative path to an absolute filesystem path.
     */
    protected function resolveSourcePath(string $src): ?string
    {
        // Already an absolute path that exists
        if (is_file($src)) {
            return $src;
        }

        // Try public_path
        $publicPath = public_path($src);
        if (is_file($publicPath)) {
            return $publicPath;
        }

        // Try storage path
        $storagePath = storage_path('app/public/' . ltrim($src, '/'));
        if (is_file($storagePath)) {
            return $storagePath;
        }

        return null;
    }

    protected function servePixum(Request $request): Response
    {
        $rawW   = $request->input('width', 200);
        $rawH   = $request->input('height', 200);
        $width  = is_numeric($rawW) ? (int) $rawW : 200;
        $height = is_numeric($rawH) ? (int) $rawH : 200;

        $path = $this->pixumGenerator->generate(max($width, 1), max($height, 1));

        return $this->serveFile($path);
    }

    protected function servePixumDefault(): Response
    {
        $path = $this->pixumGenerator->generate(200, 200);

        return $this->serveFile($path);
    }

    protected function serveFile(string $path): Response
    {
        if (!is_file($path)) {
            return new Response('Not found', 404);
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mimeMap = [
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'gif'  => 'image/gif',
            'webp' => 'image/webp',
            'bmp'  => 'image/bmp',
            'svg'  => 'image/svg+xml',
        ];

        $mime = $mimeMap[$ext] ?? 'application/octet-stream';

        return new Response(
            file_get_contents($path),
            200,
            [
                'Content-Type'  => $mime,
                'Cache-Control' => 'public, max-age=31536000',
            ]
        );
    }
}