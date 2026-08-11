<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Support;

/**
 * Scan Blade view namespaces for module skins / layout templates.
 *
 * Parses front-matter-like metadata comments (name:, type:, category:, …)
 * and discovers screenshots / skin JSON beside each blade file.
 */
class ScanForBladeTemplates
{
    /**
     * @return list<array<string, mixed>>
     */
    public function scan(
        string $templatesNamespace,
        string|false $moduleType = false,
        string|false $activeSiteTemplate = false,
        string|false $activeSiteTemplateLowerName = false
    ): array {
        unset($activeSiteTemplate); // reserved for callers / future path resolution

        if ($templatesNamespace === '') {
            return [];
        }

        $viewsHints = [];
        try {
            if (function_exists('app') && app()->bound('view')) {
                /** @var \Illuminate\View\Factory $viewFactory */
                $viewFactory = app('view');
                $finder = $viewFactory->getFinder();
                if (method_exists($finder, 'getHints')) {
                    /** @var array<string, list<string>> $viewsHints */
                    $viewsHints = $finder->getHints();
                }
            }
        } catch (\Throwable) {
            return [];
        }

        $templatesForModule = [];
        $templatesNamespaceParts = explode('::', $templatesNamespace);
        $hintKey = $templatesNamespaceParts[0];
        $templatesNamespaceSubfolder = $templatesNamespaceParts[1] ?? '';

        if ($templatesNamespaceSubfolder !== '') {
            $templatesNamespaceSubfolder = str_replace('.', DIRECTORY_SEPARATOR, $templatesNamespaceSubfolder);
        }

        if (! isset($viewsHints[$hintKey]) || ! is_array($viewsHints[$hintKey]) || $viewsHints[$hintKey] === []) {
            return [];
        }

        $moduleTypeStr = $moduleType !== false && $moduleType !== '' ? $moduleType : false;
        $activeLower = $activeSiteTemplateLowerName !== false && $activeSiteTemplateLowerName !== ''
            ? $activeSiteTemplateLowerName
            : false;

        foreach ($viewsHints[$hintKey] as $hint) {
            if (! is_string($hint) || $hint === '') {
                continue;
            }
            $folder = $hint;
            if ($templatesNamespaceSubfolder !== '') {
                $folder = $hint . DIRECTORY_SEPARATOR . $templatesNamespaceSubfolder;
            }

            $scanTemplatesResult = $this->scanFolder($folder, $moduleTypeStr, $activeLower);
            if ($scanTemplatesResult !== []) {
                $templatesForModule = array_merge($templatesForModule, $scanTemplatesResult);
            }
        }

        return $templatesForModule;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function scanFolder(
        string $folder,
        string|false $moduleType = false,
        string|false $activeSiteTemplateLowerName = false
    ): array {
        $folder = CmsHelpers::normalizePath($folder, false);

        if (! is_dir($folder)) {
            return [];
        }

        /** @var list<string> $files */
        $files = [];
        $globFiles = glob($folder . DIRECTORY_SEPARATOR . '*.blade.php');
        if (is_array($globFiles)) {
            foreach ($globFiles as $gf) {
                $files[] = $gf;
            }
        }

        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($folder, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($iterator as $file) {
                if (! $file instanceof \SplFileInfo) {
                    continue;
                }
                if ($file->isFile() && $file->getExtension() === 'php' && str_contains($file->getFilename(), '.blade.php')) {
                    $files[] = $file->getPathname();
                }
            }
        } catch (\Throwable) {
            // directory may become unreadable mid-scan
        }

        if ($files === []) {
            return [];
        }

        natsort($files);
        $files = array_values(array_unique($files));

        $configs = [];
        foreach ($files as $filename) {
            if (! is_file($filename)) {
                continue;
            }

            $parsed = $this->parseBladeFile(
                $filename,
                $folder,
                $moduleType,
                $activeSiteTemplateLowerName
            );
            if ($parsed !== null) {
                $configs[] = $parsed;
            }
        }

        return $configs;
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function parseBladeFile(
        string $filename,
        string $folder,
        string|false $moduleType,
        string|false $activeSiteTemplateLowerName
    ): ?array {
        $fin = @file_get_contents($filename);
        if ($fin === false) {
            return null;
        }

        $fin = preg_replace('/\r\n?/', "\n", $fin) ?? $fin;
        $hereDir = dirname($filename) . DIRECTORY_SEPARATOR;
        $toReturnTemp = [];

        $toReturnTemp = array_merge($toReturnTemp, $this->extractMeta($fin, $hereDir));

        $toReturnTemp['directory'] = $hereDir;

        $layoutFile = str_replace(DIRECTORY_SEPARATOR, '/', $filename);

        $skipLayoutFiles = [
            '404.php',
            'forgot_password.php',
            'login.php',
            'register.php',
            'reset_password.php',
            'layouts/sign-up.php',
        ];

        $skipLayoutBasenames = [
            '404',
            'forgot_password',
            'login',
            'register',
            'reset_password',
            'sign-up',
            'footer_cart',
        ];

        if (in_array($layoutFile, $skipLayoutFiles, true)) {
            return null;
        }

        $layoutBasename = pathinfo($layoutFile, PATHINFO_FILENAME);
        $layoutBasename = str_replace('.blade', '', $layoutBasename);
        if (in_array($layoutBasename, $skipLayoutBasenames, true)) {
            return null;
        }

        $folderNormalized = CmsHelpers::normalizePath($folder, true);
        $filenameNormalized = CmsHelpers::normalizePath($filename, false);

        $layoutFileBasename = str_replace($folderNormalized, '', $filenameNormalized);
        $layoutFileBasename = str_replace(['\\', '//'], '.', $layoutFileBasename);

        $viewName = str_replace('.blade.php', '', $layoutFileBasename);
        $toReturnTemp['layout_file'] = $viewName;

        if (! isset($toReturnTemp['name']) || ! is_string($toReturnTemp['name']) || $toReturnTemp['name'] === '') {
            $humanName = str_replace(['.', '-', '_'], ' ', $viewName);
            $humanName = preg_replace('/\bskin\s+\d+/i', '', $humanName) ?? $humanName;
            $humanName = preg_replace('/\bmw\b[\s-]*/i', '', $humanName) ?? $humanName;
            $humanName = ucwords(trim(preg_replace('/\s+/', ' ', $humanName) ?? $humanName));
            $toReturnTemp['name'] = $humanName !== '' ? $humanName : ucfirst($viewName);
        }

        $toReturnTemp['filename'] = $filename;

        $skinSettingsJson = str_ireplace('.blade.php', '.json', $filename);
        $skinSettingsJson = str_ireplace('.php', '.json', $skinSettingsJson);

        $screenshotType = 'modules';
        if (isset($toReturnTemp['type']) && $toReturnTemp['type'] === 'layout') {
            $screenshotType = 'layouts';
        }
        if ($moduleType !== false && $moduleType !== '') {
            $screenshotType = $moduleType;
        }

        $imgName = $toReturnTemp['layout_file'] . '.png';
        $imgName = str_replace(['/', '\\'], '.', $imgName);
        $imgPathModules = 'modules/' . $screenshotType . '/templates/' . $imgName;
        $imgPath = $imgPathModules;

        if ($activeSiteTemplateLowerName !== false && $activeSiteTemplateLowerName !== '') {
            $imgPath = 'templates/' . $activeSiteTemplateLowerName . '/img/screenshots/modules/'
                . $screenshotType . '/templates/' . $imgName;
        }

        $imgPathForUpdateScreenshot = $toReturnTemp['directory'] . $imgName;
        if ($activeSiteTemplateLowerName !== false && $activeSiteTemplateLowerName !== '') {
            $checkIfActiveSiteTemplate = CmsHelpers::findTemplate($activeSiteTemplateLowerName);
            if ($checkIfActiveSiteTemplate !== null) {
                $checkIfActiveSiteTemplatePath = CmsHelpers::templatePath($checkIfActiveSiteTemplate);
                if ($checkIfActiveSiteTemplatePath !== '') {
                    $imgPathForUpdateScreenshot = $checkIfActiveSiteTemplatePath
                        . '/resources/assets/img/screenshots/' . $imgPathModules;
                }
            }
        }

        $imgPathForUpdateScreenshot = str_replace(DIRECTORY_SEPARATOR, '/', $imgPathForUpdateScreenshot);
        $imgPathForUpdateScreenshot = str_replace('//', '/', $imgPathForUpdateScreenshot);
        $imgPathForUpdateScreenshot = str_replace(
            'resources/views/',
            'resources/assets/img/screenshots/',
            $imgPathForUpdateScreenshot
        );

        $screen2 = function_exists('public_path') ? public_path($imgPath) : $imgPath;
        $screenshotPublic = function_exists('asset') ? asset($imgPath) : $imgPath;

        $toReturnTemp['screenshot_path_lookup'] = $screen2;
        $toReturnTemp['screenshot_path_lookup_public'] = $screen2;
        $toReturnTemp['screenshot_path_for_update_screenshot'] = $imgPathForUpdateScreenshot;

        if (is_file($screen2)) {
            $toReturnTemp['screenshot_public_url'] = $screenshotPublic;
            $toReturnTemp['screenshot_file'] = CmsHelpers::normalizePath($screen2, false);
        }

        if (is_file($skinSettingsJson)) {
            $toReturnTemp['skin_settings_json_file'] = $skinSettingsJson;
        }

        $foundModulesInSkin = [];
        if (preg_match_all('/<module\s+type=[\'"]([^\'"]+)[\'"][^>]*>/i', $fin, $matchesFoundModulesInSkin) > 0) {
            foreach ($matchesFoundModulesInSkin[1] as $moduleTypeInSkin) {
                $moduleTypeInSkin = trim($moduleTypeInSkin);
                if ($moduleTypeInSkin !== '') {
                    $foundModulesInSkin[] = $moduleTypeInSkin;
                }
            }
            if ($foundModulesInSkin !== []) {
                $toReturnTemp['found_modules'] = array_values(array_unique($foundModulesInSkin));
            }
        }

        return $toReturnTemp;
    }

    /**
     * @return array<string, mixed>
     */
    protected function extractMeta(string $fin, string $hereDir): array
    {
        $meta = [];

        $stringFields = [
            'type',
            'is_shop',
            'hidden',
            'name',
            'is_default',
            'categories',
            'version',
            'visible',
            'description',
            'content_type',
            'tag',
        ];

        foreach ($stringFields as $field) {
            if (preg_match('/' . preg_quote($field, '/') . ':.+/', $fin, $regs) === 1) {
                $result = str_ireplace($field . ':', '', $regs[0]);
                $meta[$field] = trim($result);
            }
        }

        $meta['category'] = 'All';
        if (preg_match('/category:.+/', $fin, $regs) === 1) {
            $result = str_ireplace('category:', '', $regs[0]);
            $meta['category'] = trim($result);
        }

        if (isset($meta['categories']) && is_string($meta['categories'])) {
            $parts = explode(',', $meta['categories']);
            $meta['category'] = trim($parts[0]);
        }

        if (preg_match('/position:.+/', $fin, $regs) === 1) {
            $result = str_ireplace('position:', '', $regs[0]);
            $meta['position'] = (int) trim($result);
        } else {
            $meta['position'] = 99999;
        }

        if (preg_match('/icon:.+/', $fin, $regs) === 1) {
            $result = trim(str_ireplace('icon:', '', $regs[0]));
            $possible = $hereDir . $result;
            if (is_file($possible)) {
                $meta['icon'] = $result;
            }
        }

        if (preg_match('/image:.+/', $fin, $regs) === 1) {
            $result = trim(str_ireplace('image:', '', $regs[0]));
            $possible = $hereDir . $result;
            if (is_file($possible)) {
                $meta['image'] = $result;
            }
        }

        return $meta;
    }
}
