<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient;

use Composer\Semver\Comparator;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use MicroweberPackages\PackageManagerClient\Contracts\LocalPackageResolverInterface;
use MicroweberPackages\PackageManagerClient\Contracts\PostInstallHookInterface;
use MicroweberPackages\PackageManagerClient\Support\FilesystemHelper;
use MicroweberPackages\PackageManagerClient\Support\ZipExtractor;

/**
 * High-level package manager: search, download, install, update.
 *
 * Drop-in replacement for the legacy MicroweberComposerClient with:
 *  - install-dir detection (module / template / nwidart)
 *  - no hard dependency on Microweber helpers (paths via config)
 *  - HTTP client embedded (former microweber-packages/composer-client)
 *
 * @phpstan-type PackageMeta array<string, mixed>
 * @phpstan-type InstallResponse array{
 *     success?: string,
 *     error?: string,
 *     log?: string,
 *     redirect_to?: string,
 *     form_data_module?: string,
 *     form_data_module_params?: array<string, mixed>,
 *     message?: string
 * }
 */
class PackageManagerClient extends Client
{
    public string $logfile = '';

    private InstallDirDetector $installDirDetector;
    private ZipExtractor $zipExtractor;
    private ?CacheRepository $cache;
    private ?LocalPackageResolverInterface $localResolver;
    private ?PostInstallHookInterface $postInstallHook;

    private string $basePath;
    private string $downloadPath;
    private int $cacheTtl;

    /**
     * @param list<string>|null $packageServers
     * @param array{
     *     base_path?: string,
     *     modules_path?: string,
     *     templates_path?: string,
     *     vendor_path?: string,
     *     download_path?: string,
     *     log_path?: string,
     *     cache_ttl_seconds?: int,
     *     timeout?: int,
     *     connect_timeout?: int,
     *     verify_ssl?: bool,
     *     user_agent?: string
     * } $config
     */
    public function __construct(
        ?array $packageServers = null,
        array $config = [],
        ?CacheRepository $cache = null,
        ?LocalPackageResolverInterface $localResolver = null,
        ?PostInstallHookInterface $postInstallHook = null,
        ?InstallDirDetector $installDirDetector = null,
        ?ZipExtractor $zipExtractor = null,
    ) {
        $httpOptions = array_filter([
            'timeout' => $config['timeout'] ?? null,
            'connect_timeout' => $config['connect_timeout'] ?? null,
            'verify_ssl' => $config['verify_ssl'] ?? null,
            'user_agent' => $config['user_agent'] ?? null,
        ], static fn ($v): bool => $v !== null);

        parent::__construct($packageServers, $httpOptions);

        $this->basePath = rtrim(
            $config['base_path'] ?? (function_exists('base_path') ? base_path() : (getcwd() ?: '.')),
            '/\\'
        );
        $this->downloadPath = FilesystemHelper::resolvePath(
            (string) ($config['download_path'] ?? 'storage/cache/composer-download'),
            $this->basePath
        );
        $logPath = FilesystemHelper::resolvePath(
            (string) ($config['log_path'] ?? 'storage/logs/package-install.log'),
            $this->basePath
        );
        $this->logfile = $logPath;
        $this->cacheTtl = (int) ($config['cache_ttl_seconds'] ?? 3600);

        $this->installDirDetector = $installDirDetector ?? new InstallDirDetector([
            'base_path' => $this->basePath,
            'modules_path' => (string) ($config['modules_path'] ?? 'Modules'),
            'templates_path' => (string) ($config['templates_path'] ?? 'Templates'),
            'vendor_path' => (string) ($config['vendor_path'] ?? 'vendor'),
        ]);
        $this->zipExtractor = $zipExtractor ?? new ZipExtractor();
        $this->cache = $cache;
        $this->localResolver = $localResolver;
        $this->postInstallHook = $postInstallHook;
    }

    public function getInstallDirDetector(): InstallDirDetector
    {
        return $this->installDirDetector;
    }

    /**
     * Count packages that have a newer remote version than local.
     */
    public function countNewUpdates(): int
    {
        $searchPackages = $this->search();
        if ($searchPackages === [] || isset($searchPackages['error'])) {
            return 0;
        }

        $allPackages = $this->collectLocalPackages();
        $newUpdates = 0;

        foreach ($searchPackages as $packageName => $versions) {
            if (!is_array($versions) || !is_string($packageName)) {
                continue;
            }
            $latest = end($versions);
            if (!is_array($latest)) {
                continue;
            }

            $targetDir = isset($latest['target-dir']) && is_string($latest['target-dir'])
                ? $latest['target-dir']
                : '';
            $remoteVersion = isset($latest['version']) && is_string($latest['version'])
                ? trim($latest['version'])
                : '';

            if ($targetDir === '' || $remoteVersion === '') {
                continue;
            }

            foreach ($allPackages as $module) {
                $dirName = isset($module['dir_name']) && is_string($module['dir_name']) ? $module['dir_name'] : '';
                $localVersion = isset($module['version']) && is_string($module['version']) ? trim($module['version']) : '';
                if ($dirName === $targetDir && $localVersion !== '' && $remoteVersion !== $localVersion) {
                    if (Comparator::greaterThan($remoteVersion, $localVersion)) {
                        $newUpdates++;
                    }
                    break;
                }
            }
        }

        return $newUpdates;
    }

    /**
     * Cached count helper (uses Laravel cache when available).
     */
    public function countNewUpdatesCached(): int
    {
        if ($this->cache === null) {
            return 0;
        }

        $count = $this->cache->get('countNewUpdates');
        if (is_int($count) || is_numeric($count)) {
            return (int) $count;
        }

        return 0;
    }

    /**
     * Two-step install/update: first call downloads + asks confirm, second installs.
     *
     * @param array{require_name?: string, require_version?: string, confirm_key?: string} $params
     * @return InstallResponse
     */
    public function requestInstall(array $params): array
    {
        if (!isset($params['require_version']) || !is_string($params['require_version']) || $params['require_version'] === '') {
            $params['require_version'] = 'latest';
        }

        $requireName = isset($params['require_name']) && is_string($params['require_name'])
            ? $params['require_name']
            : '';

        if ($requireName === '') {
            return ['error' => 'Error. Package name is required.'];
        }

        $this->newLog('Request install...');
        $this->log('Searching for ' . $requireName . ' for version ' . $params['require_version']);

        /** @var PackageMeta|array{} $package */
        $package = $this->search([
            'require_version' => $params['require_version'],
            'require_name' => $requireName,
        ]);

        if ($package === [] || isset($package['error']) || !isset($package['name'])) {
            return ['error' => 'Error. Cannot find any packages.'];
        }

        $confirmKey = 'composer-confirm-key-' . bin2hex(random_bytes(8));
        if (isset($params['confirm_key']) && is_string($params['confirm_key']) && $params['confirm_key'] !== '') {
            $isConfirmed = $this->cacheGet($params['confirm_key']);
            if (is_array($isConfirmed) && isset($isConfirmed['unzipped_files_location'])) {
                $package['unzipped_files_location'] = $isConfirmed['unzipped_files_location'];
                if (isset($isConfirmed['install_target']) && is_array($isConfirmed['install_target'])) {
                    $package['_install_target'] = $isConfirmed['install_target'];
                }

                $installed = $this->install($package);

                return $installed === false
                    ? ['error' => 'Error. Cannot install package.']
                    : $installed;
            }
        }

        $dist = isset($package['dist']) && is_array($package['dist']) ? $package['dist'] : [];
        if (isset($dist['type']) && $dist['type'] === 'license_key') {
            return [
                'error' => 'You need license key to install this package',
                'message' => 'This package is premium and you must have a license key to install it',
                'form_data_module' => 'settings/group/license_edit',
                'form_data_module_params' => [
                    'require_name' => $requireName,
                    'require_version' => 'You need license key',
                ],
            ];
        }

        $downloaded = $this->downloadPackage($package, $confirmKey);
        $this->clearLog();

        if (!$downloaded) {
            return ['error' => 'Error. Cannot download package.'];
        }

        return [
            'error' => 'Please confirm installation',
            'form_data_module' => 'admin/developer_tools/package_manager/confirm_install',
            'form_data_module_params' => [
                'confirm_key' => $confirmKey,
                'require_name' => $requireName,
                'require_version' => $params['require_version'],
            ],
        ];
    }

    /**
     * Alias for requestInstall — update uses the same flow (overwrite install dir).
     *
     * @param array{require_name?: string, require_version?: string, confirm_key?: string} $params
     * @return InstallResponse
     */
    public function requestUpdate(array $params): array
    {
        return $this->requestInstall($params);
    }

    /**
     * @param PackageMeta $package
     */
    public function downloadPackage(array $package, string $confirmKey): bool
    {
        $dist = isset($package['dist']) && is_array($package['dist']) ? $package['dist'] : [];
        if (!isset($dist['url']) || !is_string($dist['url']) || $dist['url'] === '') {
            return false;
        }

        $distUrl = $dist['url'];

        try {
            $target = $this->installDirDetector->detect($package);
        } catch (\Throwable) {
            return false;
        }

        $packageFileName = 'last-package.zip';
        $packageFileDestination = $this->downloadPath . DIRECTORY_SEPARATOR . $target->directory . DIRECTORY_SEPARATOR;

        FilesystemHelper::removeDirectory($packageFileDestination);
        FilesystemHelper::ensureDirectory($packageFileDestination);

        $this->log('Downloading the package file..');

        $downloadStatus = $this->downloadBigFile(
            $distUrl,
            $packageFileDestination . $packageFileName,
            $this->logfile !== '' ? $this->logfile : false
        );

        if ($downloadStatus !== true) {
            $this->log('Download failed: ' . $downloadStatus);

            return false;
        }

        $this->log('Extract the package file..');

        try {
            $this->zipExtractor->extract(
                $packageFileDestination . $packageFileName,
                $packageFileDestination,
                true
            );
        } catch (\Throwable $e) {
            $this->log('Extract failed: ' . $e->getMessage());

            return false;
        }

        // If zip contained a single top-level folder, flatten when it wraps the package
        $this->maybeFlattenExtractedRoot($packageFileDestination);

        $scanDestination = FilesystemHelper::listFilenamesRecursive($packageFileDestination);
        foreach ($scanDestination as $value) {
            $this->log('Unzip file: ' . $value);
        }

        $composerConfirm = [
            'user' => $scanDestination,
            'packages' => $scanDestination,
            'unzipped_files_location' => $packageFileDestination,
            'install_target' => $target->toArray(),
        ];

        $this->cachePut($confirmKey, $composerConfirm);

        return true;
    }

    /**
     * Install (or update) a package that was previously downloaded.
     *
     * @param PackageMeta $package
     * @return InstallResponse|false
     */
    public function install(array $package): array|false
    {
        if (!isset($package['unzipped_files_location']) || !is_string($package['unzipped_files_location'])) {
            return false;
        }

        try {
            $target = $this->installDirDetector->detect($package);
        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }

        $packageFileDestination = rtrim($target->absolutePath, '/\\') . DIRECTORY_SEPARATOR;

        FilesystemHelper::removeDirectory($packageFileDestination);

        $source = rtrim($package['unzipped_files_location'], '/\\');
        // If source ends with trailing content folder, move its contents
        if (!FilesystemHelper::moveDirectory($source, rtrim($packageFileDestination, '/\\'))) {
            // Fallback: copy files from source into destination
            FilesystemHelper::ensureDirectory($packageFileDestination);
            FilesystemHelper::copyDirectory($source, rtrim($packageFileDestination, '/\\'));
            FilesystemHelper::removeDirectory($source);
        }

        $moduleName = is_string($package['name'] ?? null) ? (string) $package['name'] : $target->packageName;
        $moduleName = str_replace(['microweber-modules/', 'microweber-templates/', 'modules/'], '', $moduleName);

        $response = [
            'success' => 'Success. You have installed: ' . $moduleName,
            'log' => 'Done!',
        ];

        if (isset($package['notification-url']) && is_string($package['notification-url'])) {
            if (filter_var($package['notification-url'], FILTER_VALIDATE_URL)) {
                $this->notifyPackageInstall($package);
            }
        }

        if ($this->postInstallHook !== null) {
            $response = $this->postInstallHook->afterInstall($package, $response);
        }

        $this->clearLog();

        return $response;
    }

    /**
     * Detect install target without installing.
     *
     * @param PackageMeta $package
     */
    public function detectInstallDir(array $package): InstallTarget
    {
        return $this->installDirDetector->detect($package);
    }

    public function newLog(string $log): void
    {
        if ($this->logfile === '') {
            return;
        }
        FilesystemHelper::ensureDirectory(dirname($this->logfile));
        @file_put_contents($this->logfile, $log . PHP_EOL);
    }

    public function clearLog(): void
    {
        if ($this->logfile !== '' && is_file($this->logfile)) {
            @unlink($this->logfile);
        }
    }

    public function log(string $log): void
    {
        if ($this->logfile === '') {
            return;
        }
        FilesystemHelper::ensureDirectory(dirname($this->logfile));
        @file_put_contents($this->logfile, $log . PHP_EOL, FILE_APPEND);
    }

    /**
     * @return list<array{name?: string, dir_name?: string, version?: string, is_symlink?: bool|int|string, type?: string}>
     */
    private function collectLocalPackages(): array
    {
        if ($this->localResolver === null) {
            return [];
        }

        $all = [];
        foreach ($this->localResolver->modules() as $package) {
            $all[] = $package;
        }
        foreach ($this->localResolver->templates() as $package) {
            $all[] = $package;
        }

        return $all;
    }

    private function maybeFlattenExtractedRoot(string $destination): void
    {
        $entries = @scandir($destination);
        if (!is_array($entries)) {
            return;
        }
        $children = array_values(array_filter(
            $entries,
            static fn (string $e): bool => $e !== '.' && $e !== '..' && $e !== '__MACOSX'
        ));

        if (count($children) !== 1) {
            return;
        }

        $only = $destination . DIRECTORY_SEPARATOR . $children[0];
        if (!is_dir($only)) {
            return;
        }

        // Move children up one level
        $inner = @scandir($only);
        if (!is_array($inner)) {
            return;
        }
        foreach ($inner as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            @rename($only . DIRECTORY_SEPARATOR . $item, $destination . DIRECTORY_SEPARATOR . $item);
        }
        FilesystemHelper::removeDirectory($only);
    }

    private function cachePut(string $key, mixed $value): void
    {
        if ($this->cache !== null) {
            $this->cache->put($key, $value, $this->cacheTtl);

            return;
        }

        // File-based fallback for standalone / no-container use
        $file = $this->downloadPath . DIRECTORY_SEPARATOR . '.cache' . DIRECTORY_SEPARATOR . hash('sha256', $key) . '.json';
        FilesystemHelper::ensureDirectory(dirname($file));
        file_put_contents($file, (string) json_encode([
            'expires' => time() + $this->cacheTtl,
            'value' => $value,
        ]));
    }

    private function cacheGet(string $key): mixed
    {
        if ($this->cache !== null) {
            return $this->cache->get($key);
        }

        $file = $this->downloadPath . DIRECTORY_SEPARATOR . '.cache' . DIRECTORY_SEPARATOR . hash('sha256', $key) . '.json';
        if (!is_file($file)) {
            return null;
        }
        $raw = file_get_contents($file);
        if ($raw === false) {
            return null;
        }
        $data = json_decode($raw, true);
        if (!is_array($data) || !isset($data['expires'], $data['value'])) {
            return null;
        }
        if ((int) $data['expires'] < time()) {
            @unlink($file);

            return null;
        }

        return $data['value'];
    }
}
