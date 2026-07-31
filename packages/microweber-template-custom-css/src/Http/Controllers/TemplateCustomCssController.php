<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use MicroweberPackages\TemplateCustomCss\Exceptions\InvalidCssException;
use MicroweberPackages\TemplateCustomCss\Services\TemplateCustomCssManager;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class TemplateCustomCssController extends Controller
{
    public function __construct(
        protected TemplateCustomCssManager $manager,
    ) {
    }

    /**
     * POST api/current_template_save_custom_css
     * Saves live-edit CSS for the current (or specified) template.
     */
    public function saveLiveEditCss(Request $request): SymfonyResponse
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->all();

        // CMS template constants when available
        if (function_exists('app') && app()->bound('template_manager')) {
            try {
                $tm = app('template_manager');
                if (is_object($tm) && method_exists($tm, 'defineConstants')) {
                    $tm->defineConstants($data);
                }
            } catch (\Throwable) {
                // ignore
            }
        }

        // Prefer CMS LayoutsManager resolution when available (content_id / referer).
        if (function_exists('app') && app()->bound('layouts_manager')) {
            try {
                $layouts = app('layouts_manager');
                if (is_object($layouts) && method_exists($layouts, 'template_save_css')) {
                    $result = $layouts->template_save_css($data);
                    if ($result === false) {
                        return response()->json(['error' => 'Unable to save CSS'], 400);
                    }

                    return response()->json($result);
                }
            } catch (InvalidCssException $e) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'errors' => $e->getErrors(),
                ], 422);
            } catch (\Throwable) {
                // fall through to package-only path
            }
        }

        try {
            $result = $this->manager->saveLiveEditFromRequest($data);
        } catch (InvalidCssException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'errors' => $e->getErrors(),
            ], 422);
        }

        if ($result === false) {
            return response()->json(['error' => 'Unable to save CSS'], 400);
        }

        return response()->json($result);
    }

    /**
     * POST api/layouts/template_remove_custom_css
     */
    public function removeCustomCss(Request $request): SymfonyResponse
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->all();

        if (function_exists('app') && app()->bound('template_manager')) {
            try {
                $tm = app('template_manager');
                if (is_object($tm) && method_exists($tm, 'defineConstants')) {
                    $tm->defineConstants($data);
                }
            } catch (\Throwable) {
                // ignore
            }
        }

        if (function_exists('app') && app()->bound('layouts_manager')) {
            try {
                $layouts = app('layouts_manager');
                if (is_object($layouts) && method_exists($layouts, 'template_remove_custom_css')) {
                    $result = $layouts->template_remove_custom_css($data);
                    if ($result === false) {
                        return response()->json(['error' => 'Unable to remove CSS'], 400);
                    }

                    return response()->json($result);
                }
            } catch (\Throwable) {
                // fall through
            }
        }

        $result = $this->manager->removeLiveEditFromRequest($data);
        if ($result === false) {
            return response()->json(['error' => 'Unable to remove CSS'], 400);
        }

        return response()->json($result);
    }

    /**
     * ANY api/template/print_custom_css
     */
    public function printCustomCss(Request $request): Response
    {
        $contents = $this->manager->customCss()->getCustomCss();
        $etag = md5($contents);

        if ($request->header('If-None-Match') === $etag) {
            return response()->make('', 304);
        }

        return response($contents, 200, [
            'Content-Type' => 'text/css',
            'ETag' => $etag,
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    /**
     * POST api/template/save_custom_css — save user custom CSS option.
     */
    public function saveCustomCss(Request $request): SymfonyResponse
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $cssRaw = $request->input('css', $request->input('custom_css', $request->input('option_value', '')));
        $css = is_string($cssRaw) ? $cssRaw : '';

        try {
            $saved = $this->manager->customCss()->saveCustomCss($css);
        } catch (InvalidCssException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'errors' => $e->getErrors(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'content' => $saved,
            'url' => $this->manager->customCss()->getCustomCssUrl(),
        ]);
    }

    /**
     * POST api/template/validate_css — dry-run CSS validation.
     */
    public function validateCss(Request $request): SymfonyResponse
    {
        $cssRaw = $request->input('css', $request->input('css_file_content', ''));
        $css = is_string($cssRaw) ? $cssRaw : '';
        $result = $this->manager->validate($css);

        return response()->json($result, $result['valid'] ? 200 : 422);
    }

    /**
     * GET api/template/live_edit_css_url
     */
    public function liveEditCssUrl(Request $request): SymfonyResponse
    {
        $template = $request->query('template');
        $templateName = is_string($template) ? $template : null;
        $url = $this->manager->liveEdit()->getLiveEditCssUrl($templateName);

        return response()->json([
            'url' => $url,
            'path' => $this->manager->liveEdit()->getPath($templateName),
            'content' => $this->manager->liveEdit()->getContent($templateName),
        ]);
    }

    protected function isAdmin(): bool
    {
        $gate = $this->manager->getConfig()['admin_gate'] ?? null;
        if (is_callable($gate)) {
            return (bool) $gate();
        }

        if (function_exists('is_admin')) {
            return (bool) is_admin();
        }

        try {
            if (function_exists('auth') && auth()->check()) {
                $user = auth()->user();
                if (is_object($user) && isset($user->is_admin)) {
                    return (bool) $user->is_admin;
                }

                return true;
            }
        } catch (\Throwable) {
            // ignore
        }

        // Standalone test environments without auth: allow when app.env is testing
        try {
            if (function_exists('app') && app()->environment('testing')) {
                return true;
            }
        } catch (\Throwable) {
            // ignore
        }

        return false;
    }
}
