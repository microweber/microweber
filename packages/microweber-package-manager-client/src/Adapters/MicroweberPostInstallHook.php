<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Adapters;

use MicroweberPackages\PackageManagerClient\Contracts\PostInstallHookInterface;
use MicroweberPackages\PackageManagerClient\InstallDirDetector;

/**
 * Microweber-specific post-install: cache bust, scan modules, redirect URLs.
 *
 * @phpstan-type PackageMeta array<string, mixed>
 */
class MicroweberPostInstallHook implements PostInstallHookInterface
{
    /**
     * @param PackageMeta $package
     * @param array{success?: string, log?: string, redirect_to?: string} $response
     * @return array{success?: string, log?: string, redirect_to?: string}
     */
    public function afterInstall(array $package, array $response): array
    {
        $type = isset($package['type']) && is_string($package['type']) ? $package['type'] : 'microweber-module';
        $moduleName = isset($package['name']) && is_string($package['name']) ? $package['name'] : '';
        $moduleName = str_replace(['microweber-modules/', 'microweber-templates/', 'modules/'], '', $moduleName);

        $installed = function_exists('mw_is_installed') ? (bool) call_user_func('mw_is_installed') : false;

        if ($installed) {
            $moduleLink = null;
            if (function_exists('module_admin_url')) {
                /** @var mixed $link */
                $link = call_user_func('module_admin_url', $moduleName);
                $moduleLink = is_string($link) ? $link : null;
            }

            if ($moduleLink !== null && $moduleLink !== '') {
                if ($type === InstallDirDetector::TYPE_TEMPLATE && function_exists('admin_url')) {
                    /** @var mixed $tplLink */
                    $tplLink = call_user_func('admin_url', 'settings?group=template');
                    if (is_string($tplLink)) {
                        $response['success'] = ($response['success'] ?? '') . '<br /> <a href="' . $tplLink . '">Visit template settings</a>';
                        $response['redirect_to'] = $tplLink;
                    }
                } elseif (function_exists('admin_url')) {
                    $response['success'] = ($response['success'] ?? '') . '<br /> <a href="' . $moduleLink . '">Visit the module</a>';
                    /** @var mixed $redir */
                    $redir = call_user_func('admin_url', 'module/view?type=' . $moduleName);
                    if (is_string($redir)) {
                        $response['redirect_to'] = $redir;
                    }
                }
            }

            try {
                if (function_exists('app')) {
                    /** @var mixed $app */
                    $app = call_user_func('app');
                    if (is_object($app) && isset($app->update) && is_object($app->update) && method_exists($app->update, 'post_update')) {
                        $app->update->post_update();
                    }
                    if (is_object($app) && isset($app->cache_manager) && is_object($app->cache_manager) && method_exists($app->cache_manager, 'delete')) {
                        foreach (['db', 'update', 'elements', 'templates', 'modules', 'livewire-marketplace'] as $tag) {
                            $app->cache_manager->delete($tag);
                        }
                    }
                }
            } catch (\Throwable) {
                // best-effort
            }

            if (class_exists(\Illuminate\Support\Facades\Cache::class)) {
                try {
                    \Illuminate\Support\Facades\Cache::forget('livewire-marketplace');
                } catch (\Throwable) {
                    // ignore
                }
            }
        }

        return $response;
    }
}
