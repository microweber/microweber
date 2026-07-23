<?php

namespace MicroweberPackages\MediaPixum\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use MicroweberPackages\MediaPixum\PixumGenerator;

/**
 * Serves placeholder (pixum) images.
 *
 * Returns proper HTTP responses — no exit() calls.
 */
class PixumController extends Controller
{
    public function __construct(
        protected PixumGenerator $generator
    ) {
    }

    /**
     * GET /pixum_img — serve a placeholder image.
     */
    public function serve(Request $request): Response
    {
        $defaultWidth = (int) config('media-pixum.default_width', 200);
        $defaultHeight = (int) config('media-pixum.default_height', 200);

        $rawW = $request->input('width', $defaultWidth);
        $rawH = $request->input('height', $defaultWidth);
        $width = is_numeric($rawW) ? (int) $rawW : $defaultWidth;
        $height = is_numeric($rawH) ? (int) $rawH : $defaultHeight;

        $path = $this->generator->generate(max($width, 1), max($height, 1));

        return $this->serveFile($path);
    }

    /**
     * Serve a pixum file as an HTTP response.
     */
    protected function serveFile(string $path): Response
    {
        if (!is_file($path)) {
            return new Response('Not found', 404, ['Content-Type' => 'text/plain']);
        }

        $content = file_get_contents($path);
        if ($content === false) {
            return new Response('Read error', 500, ['Content-Type' => 'text/plain']);
        }

        return new Response(
            $content,
            200,
            [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=31536000',
            ]
        );
    }
}