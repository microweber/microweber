<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient;

use Composer\Semver\Comparator;
use MicroweberPackages\PackageManagerClient\Contracts\LocalPackageResolverInterface;

/**
 * Formats remote package metadata with local install / update status.
 *
 * @phpstan-type PackageMeta array<string, mixed>
 * @phpstan-type LocalPackage array{name?: string, dir_name?: string, version?: string, is_symlink?: bool|int|string, type?: string}
 */
class PackageFormatter
{
    /** @var list<LocalPackage> */
    private array $allPackages = [];

    private bool $loaded = false;

    public function __construct(
        private readonly ?LocalPackageResolverInterface $localResolver = null,
    ) {
    }

    /**
     * Static helper matching legacy MicroweberComposerPackage::format().
     *
     * @param PackageMeta $version
     * @return PackageMeta
     */
    public static function format(array $version, ?LocalPackageResolverInterface $resolver = null): array
    {
        if ($resolver === null && function_exists('app')) {
            try {
                /** @var mixed $app */
                $app = call_user_func('app');
                if (
                    is_object($app)
                    && method_exists($app, 'bound')
                    && method_exists($app, 'make')
                    && $app->bound(LocalPackageResolverInterface::class)
                ) {
                    $resolved = $app->make(LocalPackageResolverInterface::class);
                    if ($resolved instanceof LocalPackageResolverInterface) {
                        $resolver = $resolved;
                    }
                }
            } catch (\Throwable) {
                $resolver = null;
            }
        }

        return (new self($resolver))->formatInstance($version);
    }

    /**
     * @param PackageMeta $version
     * @return PackageMeta
     */
    public function formatInstance(array $version): array
    {
        $this->ensureLocalLoaded();

        $version['release_date'] = date('Y-m-d H:i:s');
        $version['latest_version'] = $version;

        if (!isset($version['type'])) {
            return $version;
        }

        $version['is_paid'] = false;
        $version['available_for_install'] = true;

        $dist = isset($version['dist']) && is_array($version['dist']) ? $version['dist'] : [];
        if (isset($dist['type']) && $dist['type'] === 'license_key') {
            $version['is_paid'] = true;
            $version['available_for_install'] = false;
            $extra = isset($version['extra']) && is_array($version['extra']) ? $version['extra'] : [];
            $whmcs = isset($extra['whmcs']) && is_array($extra['whmcs']) ? $extra['whmcs'] : [];
            if (isset($whmcs['buy_link']) && is_string($whmcs['buy_link'])) {
                $version['buy_link'] = $whmcs['buy_link'];
            }
        }

        if (!isset($version['target-dir'])) {
            $version['target-dir'] = '';
        }

        $version['is_symlink'] = false;
        $version['has_update'] = false;
        $version['demo_link'] = false;
        $version['screenshot_link'] = false;

        $extra = isset($version['extra']) && is_array($version['extra']) ? $version['extra'] : [];
        if (isset($extra['preview_url'])) {
            $version['demo_link'] = $extra['preview_url'];
        }
        $meta = isset($extra['_meta']) && is_array($extra['_meta']) ? $extra['_meta'] : [];
        if (isset($meta['screenshot'])) {
            $version['screenshot_link'] = $meta['screenshot'];
        }

        $type = is_string($version['type']) ? $version['type'] : '';
        if (in_array($type, ['library', 'composer-plugin', 'application'], true)) {
            return $version;
        }

        $currentInstall = false;
        $targetDir = is_string($version['target-dir']) ? $version['target-dir'] : '';
        /** @var PackageMeta $latestVersion */
        $latestVersion = $version['latest_version'];
        $remoteVersion = isset($latestVersion['version']) && is_string($latestVersion['version'])
            ? trim($latestVersion['version'])
            : '';

        foreach ($this->allPackages as $module) {
            $dirName = isset($module['dir_name']) && is_string($module['dir_name']) ? $module['dir_name'] : '';
            if ($targetDir !== '' && $dirName === $targetDir) {
                $currentInstall = [
                    'composer_type' => $type,
                    'local_type' => $type,
                    'module' => $module['name'] ?? '',
                    'module_details' => $module,
                ];

                if (isset($module['version']) && is_string($module['version']) && $remoteVersion !== '') {
                    $localVersion = trim($module['version']);
                    if ($remoteVersion !== $localVersion && Comparator::greaterThan($remoteVersion, $localVersion)) {
                        $version['has_update'] = true;
                    }
                }

                if (!empty($module['is_symlink'])) {
                    $version['has_update'] = false;
                    $version['is_symlink'] = true;
                }

                break;
            }
        }

        $version['current_install'] = $currentInstall;

        return $version;
    }

    private function ensureLocalLoaded(): void
    {
        if ($this->loaded) {
            return;
        }
        $this->loaded = true;
        $this->allPackages = [];

        if ($this->localResolver === null) {
            return;
        }

        foreach ($this->localResolver->modules() as $package) {
            $this->allPackages[] = $package;
        }
        foreach ($this->localResolver->templates() as $package) {
            $this->allPackages[] = $package;
        }
    }

    public function resetLocalCache(): void
    {
        $this->loaded = false;
        $this->allPackages = [];
    }
}
