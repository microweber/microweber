<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;

class TemplateFontsController extends Controller
{
    public function __construct(
        protected TemplateFontsManager $fonts,
    ) {
    }

    public function getFonts(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->fonts->getAvailableFonts(),
        ]);
    }

    public function getFavoriteFonts(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->fonts->getEnabledFonts(),
        ]);
    }

    public function removeFavoriteFont(Request $request): JsonResponse
    {
        $fontFamily = $request->input('font', $request->get('font'));
        if (!is_string($fontFamily) || trim($fontFamily) === '') {
            return response()->json([
                'success' => false,
                'message' => 'Font family is required',
            ], 422);
        }

        $this->fonts->removeFont(trim($fontFamily));

        return response()->json([
            'success' => true,
            'message' => 'Font removed from favorites',
        ]);
    }

    public function saveTemplateFonts(Request $request): JsonResponse
    {
        $fontFamily = $request->input('fonts', $request->get('fonts'));

        if (is_array($fontFamily)) {
            foreach ($fontFamily as $font) {
                if (is_string($font) && trim($font) !== '') {
                    $this->fonts->enableFont(trim($font), 'google');
                }
            }
        } elseif (is_string($fontFamily) && trim($fontFamily) !== '') {
            $this->fonts->enableFont(trim($fontFamily), 'google');
        }

        return response()->json([
            'success' => true,
            'message' => 'Font saved successfully.',
        ]);
    }

    public function uploadCustomFont(Request $request): JsonResponse
    {
        $file = $request->file('font') ?? $request->file('file');
        if ($file === null) {
            return response()->json([
                'success' => false,
                'message' => 'Font file is required',
            ], 422);
        }

        $family = $request->input('family');
        $family = is_string($family) ? $family : null;

        $result = $this->fonts->uploadCustomFont($file, $family);

        if ($result['success'] !== true) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Upload failed',
            ], 422);
        }

        $font = $result['font'] ?? null;

        return response()->json([
            'success' => true,
            'message' => 'Custom font uploaded successfully.',
            'data' => $font !== null ? [
                'id' => $font->id,
                'family' => $font->family,
                'provider' => $font->provider,
                'css_url' => $font->css_url,
                'file_url' => $font->file_url,
            ] : null,
        ]);
    }

    public function printCustomCssFonts(): Response
    {
        $contents = $this->fonts->getFontsStylesheetCss();

        return response($contents, 200, [
            'Content-Type' => 'text/css; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
