<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Adapters;

use MicroweberPackages\PackageManagerClient\Contracts\LocalPackageResolverInterface;

/**
 * Resolves locally installed modules/templates via Microweber's update manager.
 *
 * Bound only when the CMS is present (optional adapter — not used standalone).
 *
 * @phpstan-type LocalPackage array{name?: string, dir_name?: string, version?: string, is_symlink?: bool|int|string, type?: string}
 */
class MicroweberLocalPackageResolver implements LocalPackageResolverInterface
{
    /**
     * @return list<LocalPackage>
     */
    public function modules(): array
    {
        $data = $this->collect();

        /** @var list<LocalPackage> $modules */
        $modules = isset($data['modules']) && is_array($data['modules']) ? array_values($data['modules']) : [];

        return $modules;
    }

    /**
     * @return list<LocalPackage>
     */
    public function templates(): array
    {
        $data = $this->collect();

        /** @var list<LocalPackage> $templates */
        $templates = isset($data['templates']) && is_array($data['templates']) ? array_values($data['templates']) : [];

        return $templates;
    }

    /**
     * @return array{modules?: list<LocalPackage>, templates?: list<LocalPackage>}
     */
    private function collect(): array
    {
        if (!function_exists('app')) {
            return [];
        }

        try {
            /** @var mixed $app */
            $app = call_user_func('app');
            if (!is_object($app) || !isset($app->update)) {
                return [];
            }
            $update = $app->update;
            if (!is_object($update) || !method_exists($update, 'collect_local_data')) {
                return [];
            }
            $data = $update->collect_local_data();
            if (!is_array($data)) {
                return [];
            }

            /** @var array{modules?: list<LocalPackage>, templates?: list<LocalPackage>} $result */
            $result = [];
            if (isset($data['modules']) && is_array($data['modules'])) {
                /** @var list<LocalPackage> $modules */
                $modules = array_values($data['modules']);
                $result['modules'] = $modules;
            }
            if (isset($data['templates']) && is_array($data['templates'])) {
                /** @var list<LocalPackage> $templates */
                $templates = array_values($data['templates']);
                $result['templates'] = $templates;
            }

            return $result;
        } catch (\Throwable) {
            return [];
        }
    }
}
