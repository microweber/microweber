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
                    'error' => (config('app.debug') ? $e->getMessage() : 'An unexpected error occurred.'),
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
                'error' => (config('app.debug') ? $e->getMessage() : 'An unexpected error occurred.'),
                'errors' => $e->getErrors(),
            ], 422);
        }

        if ($result === false) {
            return response()->json(['error' => 'Unable to save CSS'], 400);
        }

        return response()->json($result);
    }

    /**
     * POST api/current_template_save_custom_js
     *
     * Saves live-edit custom JS for the current (or specified) template — the
     * same per-template live_edit.js layer the AI apply_js tool writes to, served
     * deferred in the published page footer. Overwrite semantics (the code editor
     * sends the full edited JS), mirrored to the remote disk when userfiles are on
     * S3. No validation: never lose a save.
     */
    public function saveLiveEditJs(Request $request): SymfonyResponse
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $jsRaw = $request->input('js_file_content', $request->input('js', ''));
        $js = is_string($jsRaw) ? $jsRaw : '';

        $templateRaw = $request->input('active_site_template', $request->input('template'));
        $template = (is_string($templateRaw) && $templateRaw !== '') ? $templateRaw : null;

        try {
            $saved = $this->manager->liveEdit()->saveLiveEditJsContent($js, $template);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Unable to save JS: ' . $e->getMessage()], 400);
        }

        return response()->json([
            'success' => true,
            'content' => $saved,
        ]);
    }

    /**
     * POST api/page_save_custom_css
     *
     * Save per-PAGE custom CSS keyed by content_id — the page-scoped sibling of
     * saveLiveEditCss. Overwrite semantics (the editor sends the full CSS),
     * validated + mirrored to the remote disk; served as the final stylesheet on
     * that page so it overrides the global live_edit layer.
     */
    public function savePageCss(Request $request): SymfonyResponse
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $contentId = (int) $request->input('content_id', 0);
        if ($contentId <= 0) {
            return response()->json(['error' => 'content_id is required'], 422);
        }

        $cssRaw = $request->input('css', $request->input('css_file_content', ''));
        $css = is_string($cssRaw) ? $cssRaw : '';

        try {
            $saved = $this->manager->liveEdit()->savePageCssContent($contentId, $css);
        } catch (InvalidCssException $e) {
            return response()->json([
                'error' => (config('app.debug') ? $e->getMessage() : 'An unexpected error occurred.'),
                'errors' => $e->getErrors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Unable to save CSS: ' . $e->getMessage()], 400);
        }

        return response()->json([
            'success' => true,
            'content_id' => $contentId,
            'path' => $saved,
            'url' => $this->manager->liveEdit()->getPageCssUrl($contentId),
        ]);
    }

    /**
     * POST api/page_save_custom_js
     *
     * Save per-PAGE custom JS keyed by content_id — the page-scoped sibling of
     * saveLiveEditJs. Overwrite semantics, mirrored to the remote disk; served
     * deferred in the page footer (published page only). No validation.
     */
    public function savePageJs(Request $request): SymfonyResponse
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $contentId = (int) $request->input('content_id', 0);
        if ($contentId <= 0) {
            return response()->json(['error' => 'content_id is required'], 422);
        }

        $jsRaw = $request->input('js', $request->input('js_file_content', ''));
        $js = is_string($jsRaw) ? $jsRaw : '';

        try {
            $saved = $this->manager->liveEdit()->savePageJsContent($contentId, $js);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Unable to save JS: ' . $e->getMessage()], 400);
        }

        return response()->json([
            'success' => true,
            'content_id' => $contentId,
            'path' => $saved,
            'url' => $this->manager->liveEdit()->getPageJsUrl($contentId),
        ]);
    }

    /**
     * GET|POST api/page_get_custom_style
     *
     * Read a page's current per-content-id custom CSS + JS (for the editor).
     */
    public function getPageCustomStyle(Request $request): SymfonyResponse
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $contentId = (int) ($request->input('content_id') ?? $request->query('content_id', 0));
        $le = $this->manager->liveEdit();

        return response()->json([
            'content_id' => $contentId,
            'css' => $le->getPageCssContent($contentId),
            'js' => $le->getPageJsContent($contentId),
            'css_url' => $le->getPageCssUrl($contentId),
            'js_url' => $le->getPageJsUrl($contentId),
        ]);
    }

    /**
     * POST api/current_template_undo_custom_style
     *
     * Undo the most recent Live-Edit CSS or JS change (type=css|js) by restoring
     * the previous content from the snapshot history (kept per template, up to
     * the configured limit — default 1000). Returns the restored content so the
     * editor can re-apply it, plus how many further undo steps remain.
     */
    public function undoLiveEditStyle(Request $request): SymfonyResponse
    {
        if (!$this->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $typeRaw = $request->input('type', 'css');
        $type = ($typeRaw === 'js') ? 'js' : 'css';

        $templateRaw = $request->input('active_site_template', $request->input('template'));
        $template = (is_string($templateRaw) && $templateRaw !== '') ? $templateRaw : null;

        try {
            $restored = $this->manager->liveEdit()->undoLiveEdit($type, $template);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Unable to undo: ' . $e->getMessage()], 400);
        }

        if ($restored === null) {
            return response()->json([
                'success' => false,
                'message' => 'Nothing to undo.',
                'remaining' => 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'type' => $type,
            'content' => $restored,
            'remaining' => $this->manager->liveEdit()->undoCount($type, $template),
        ]);
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
                'error' => (config('app.debug') ? $e->getMessage() : 'An unexpected error occurred.'),
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
