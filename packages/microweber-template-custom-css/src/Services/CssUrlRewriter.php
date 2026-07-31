<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Services;

/**
 * Rewrites absolute media/userfiles URLs in CSS for portable storage,
 * and restores them for backup/export (matching CMS backup behaviour).
 *
 * On save (file on disk under userfiles/css/{template}/):
 *   userfiles_url()  →  ../../
 *   ././media/       →  {userfiles_url}media/
 *
 * On backup export the CMS replaces site URLs with tokens; this helper
 * exposes the same transform so standalone apps can reuse it.
 */
class CssUrlRewriter
{
    public function __construct(
        protected string $userfilesUrl = '',
        protected string $siteUrl = '',
    ) {
        $this->userfilesUrl = rtrim($userfilesUrl, '/') . ($userfilesUrl !== '' ? '/' : '');
        $this->siteUrl = rtrim($siteUrl, '/') . ($siteUrl !== '' ? '/' : '');
    }

    /**
     * Prepare CSS for writing to userfiles/css/{template}/live_edit.css.
     * Matches legacy TemplateLiveEditCss::saveLiveEditCssContent() rewrites.
     */
    public function forStorage(string $css): string
    {
        if ($css === '') {
            return $css;
        }

        $result = $css;

        if ($this->userfilesUrl !== '' && $this->userfilesUrl !== '/') {
            // Fix double-relative media refs first
            $result = str_ireplace('././media/', $this->userfilesUrl . 'media/', $result);
            // Store portable relative paths from userfiles/css/{template}/
            $result = str_ireplace($this->userfilesUrl, '../../', $result);
        } else {
            $result = str_ireplace('././media/', '../../media/', $result);
        }

        return $result;
    }

    /**
     * Expand relative paths back to absolute userfiles URLs (for preview/API).
     */
    public function forDisplay(string $css): string
    {
        if ($css === '' || $this->userfilesUrl === '' || $this->userfilesUrl === '/') {
            return $css;
        }

        return str_ireplace('../../', $this->userfilesUrl, $css);
    }

    /**
     * Replace absolute site URL with a portable token (backup export).
     * Mirrors Modules/Backup/Formats/ZipBatchBackup CSS handling.
     */
    public function forBackupExport(string $css, string $siteUrlToken = '{SITE_URL}'): string
    {
        if ($css === '') {
            return $css;
        }

        if (function_exists('app') && app()->bound('url_manager')) {
            try {
                $urlManager = app('url_manager');
                if (is_object($urlManager) && method_exists($urlManager, 'replace_site_url')) {
                    $replaced = $urlManager->replace_site_url($css);
                    if (is_string($replaced)) {
                        return $replaced;
                    }
                }
            } catch (\Throwable) {
                // fall through
            }
        }

        if ($this->siteUrl !== '' && $this->siteUrl !== '/') {
            return str_ireplace($this->siteUrl, $siteUrlToken, $css);
        }

        return $css;
    }

    /**
     * Restore site URL tokens after backup import.
     */
    public function forBackupImport(string $css, string $siteUrlToken = '{SITE_URL}'): string
    {
        if ($css === '' || $this->siteUrl === '' || $this->siteUrl === '/') {
            return $css;
        }

        return str_ireplace($siteUrlToken, $this->siteUrl, $css);
    }

    public function getUserfilesUrl(): string
    {
        return $this->userfilesUrl;
    }

    public function setUserfilesUrl(string $url): void
    {
        $this->userfilesUrl = rtrim($url, '/') . ($url !== '' ? '/' : '');
    }

    public function setSiteUrl(string $url): void
    {
        $this->siteUrl = rtrim($url, '/') . ($url !== '' ? '/' : '');
    }
}
