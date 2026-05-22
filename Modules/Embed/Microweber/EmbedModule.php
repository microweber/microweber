<?php

namespace Modules\Embed\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Embed\Filament\EmbedModuleSettings;

class EmbedModule extends BaseModule
{
    public static string $name = 'Embed';
    public static string $module = 'embed';
    public static string $icon = 'modules.embed-icon';
    public static string $categories = 'miscellaneous';
    public static int $position = 38;
    public static string $settingsComponent = EmbedModuleSettings::class;
    public static string $templatesNamespace = 'modules.embed::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        $sourceCode = get_option('source_code', $this->params['id']);

        // AI-132 / SEC-01 (cycle-114 2026-05-09): defense-in-depth
        // audit log. The `source_code` option is gated to admin
        // authoring via EmbedModuleSettings::canAccess(). The
        // rendered output is RAW HTML / iframe / script emitted
        // to every visitor — if a future bug exposes the option-
        // set path to a non-admin role, the first signal would be
        // an unexpected payload here. Log every non-empty render
        // so an audit trail exists.
        if (!empty($sourceCode) && function_exists('logger')) {
            try {
                logger()->info('embed.render', [
                    'module_id' => $this->params['id'] ?? null,
                    'is_admin' => function_exists('is_admin') ? is_admin() : null,
                    'src_len' => strlen($sourceCode),
                ]);
            } catch (\Throwable $e) {
                // Logging is best-effort; never break the render
                // pipeline because the log channel is misconfigured.
            }
        }

        $hideInLiveEdit = get_option('hide_in_live_edit', $this->params['id']);
        $isLiveEdit = is_live_edit();

        if ($hideInLiveEdit && $isLiveEdit) {
            return lnotif(_e('Click to edit Embed Code', true));
        }

        if(trim($sourceCode) == ''){
            return lnotif(_e('Click to edit Embed Code', true));
        }

        if ($sourceCode) {
            $viewData['source_code'] = $sourceCode;
        }

        // task-2026-05-22-ce249e / AI-920 — wire code_type selector.
        // Pre-fix: code_type (html/css/javascript) was defined in settings
        // but never read — all embeds output raw {!! source_code !!} without
        // wrapping tags. CSS embeds were missing <style> tags (styles didn't
        // apply); JavaScript embeds were missing <script> tags (code didn't
        // execute).
        $codeType = get_option('code_type', $this->params['id']) ?? 'html';
        $viewData['code_type'] = in_array($codeType, ['html', 'css', 'javascript']) ? $codeType : 'html';

        $template = $viewData['template'] ?? 'default';
        if (!view()->exists(static::$templatesNamespace . '.' . $template)) {
            $template = 'default';
        }

        return view(static::$templatesNamespace . '.' . $template, $viewData);
    }
}
