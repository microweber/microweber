<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient;

use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\PackageManagerClient\Adapters\MicroweberLocalPackageResolver;
use MicroweberPackages\PackageManagerClient\Adapters\MicroweberPostInstallHook;
use MicroweberPackages\PackageManagerClient\Contracts\LocalPackageResolverInterface;
use MicroweberPackages\PackageManagerClient\Contracts\PostInstallHookInterface;

class PackageManagerClientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/package-manager-client.php',
            'package-manager-client'
        );

        $this->app->singleton(InstallDirDetector::class, function ($app): InstallDirDetector {
            $config = $this->configArray($app);

            return new InstallDirDetector([
                'base_path' => $app->basePath(),
                'modules_path' => $this->stringConfig($config, 'modules_path', 'Modules'),
                'templates_path' => $this->stringConfig($config, 'templates_path', 'Templates'),
                'vendor_path' => $this->stringConfig($config, 'vendor_path', 'vendor'),
            ]);
        });

        $this->app->singleton(PackageManagerClient::class, function ($app): PackageManagerClient {
            $config = $this->configArray($app);

            $servers = $config['package_servers'] ?? [];
            /** @var list<string> $serversList */
            $serversList = [];
            if (is_array($servers)) {
                foreach ($servers as $s) {
                    if (is_string($s) && $s !== '') {
                        $serversList[] = $s;
                    }
                }
            }

            $http = isset($config['http']) && is_array($config['http']) ? $config['http'] : [];

            $cacheStore = $config['cache_store'] ?? null;
            $cache = null;
            if ($app->bound('cache')) {
                /** @var CacheFactory $cacheFactory */
                $cacheFactory = $app->make('cache');
                $cache = is_string($cacheStore) && $cacheStore !== ''
                    ? $cacheFactory->store($cacheStore)
                    : $cacheFactory->store();
            }

            $localResolver = $app->bound(LocalPackageResolverInterface::class)
                ? $app->make(LocalPackageResolverInterface::class)
                : null;

            $postInstall = $app->bound(PostInstallHookInterface::class)
                ? $app->make(PostInstallHookInterface::class)
                : null;

            return new PackageManagerClient(
                packageServers: $serversList !== [] ? $serversList : null,
                config: [
                    'base_path' => $app->basePath(),
                    'modules_path' => $this->stringConfig($config, 'modules_path', 'Modules'),
                    'templates_path' => $this->stringConfig($config, 'templates_path', 'Templates'),
                    'vendor_path' => $this->stringConfig($config, 'vendor_path', 'vendor'),
                    'download_path' => $this->stringConfig($config, 'download_path', 'storage/cache/composer-download'),
                    'log_path' => $this->stringConfig($config, 'log_path', 'storage/logs/package-install.log'),
                    'cache_ttl_seconds' => $this->intConfig($config, 'cache_ttl_seconds', 3600),
                    'timeout' => $this->intFromArray($http, 'timeout', 30),
                    'connect_timeout' => $this->intFromArray($http, 'connect_timeout', 10),
                    'verify_ssl' => (bool) ($http['verify_ssl'] ?? true),
                    'user_agent' => $this->stringFromArray($http, 'user_agent', 'MicroweberPackageManagerClient/1.0'),
                ],
                cache: $cache,
                localResolver: $localResolver instanceof LocalPackageResolverInterface ? $localResolver : null,
                postInstallHook: $postInstall instanceof PostInstallHookInterface ? $postInstall : null,
                installDirDetector: $app->make(InstallDirDetector::class),
            );
        });

        $this->app->alias(PackageManagerClient::class, 'package-manager-client');

        $this->app->bind(Client::class, static fn ($app): PackageManagerClient => $app->make(PackageManagerClient::class));

        $this->registerMicroweberIntegration();
    }

    /**
     * Microweber CMS integration: bind the CMS implementations of the package
     * contracts and, when a client is resolved, feed it the installed licenses
     * and any white-label marketplace repository URLs. Kept here (not in the
     * CMS service provider) so the package owns its own wiring; every CMS
     * touch-point is guarded so a standalone app is unaffected.
     */
    private function registerMicroweberIntegration(): void
    {
        $this->app->singleton(LocalPackageResolverInterface::class, MicroweberLocalPackageResolver::class);
        $this->app->singleton(PostInstallHookInterface::class, MicroweberPostInstallHook::class);

        $this->app->afterResolving(
            PackageManagerClient::class,
            static function (PackageManagerClient $client): void {
                // Licenses: once installed they live in the DB; DURING install the
                // DB isn't ready, so they come from the JSON license file the
                // installer saves (system_licenses_manager->getFileLicenses()). Both
                // paths must feed the client — otherwise premium package installs
                // triggered from the InstallController (e.g. requestInstall) would run
                // unlicensed.
                if (function_exists('mw_is_installed') && mw_is_installed()) {
                    try {
                        if (class_exists(\MicroweberPackages\SystemLicenses\Models\SystemLicense::class)) {
                            $licenses = \MicroweberPackages\SystemLicenses\Models\SystemLicense::all();
                            if ($licenses !== null) {
                                $client->setLicenses($licenses->toArray());
                            }
                        }
                    } catch (\Throwable) {
                        // DB may not be ready during early boot.
                    }
                } else {
                    try {
                        if (function_exists('app') && app()->bound('system_licenses_manager')) {
                            $fileLicenses = app('system_licenses_manager')->getFileLicenses();
                            if (is_array($fileLicenses) && $fileLicenses !== []) {
                                $client->setLicenses($fileLicenses);
                            }
                        }
                    } catch (\Throwable) {
                        // No license file yet / manager unavailable (standalone).
                    }
                }

                // White-label marketplace repository URLs.
                if (function_exists('get_white_label_config')) {
                    try {
                        $settings = get_white_label_config();
                        if (is_array($settings) && !empty($settings['marketplace_repositories_urls'])) {
                            $urls = $settings['marketplace_repositories_urls'];
                            $client->setPackageServers(is_array($urls) ? $urls : [$urls]);
                        }
                    } catch (\Throwable) {
                        // ignore
                    }
                }
            }
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/package-manager-client.php' => config_path('package-manager-client.php'),
            ], 'package-manager-client-config');
        }
    }

    /**
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @return array<string, mixed>
     */
    private function configArray($app): array
    {
        try {
            /** @var mixed $raw */
            $raw = $app->make('config')->get('package-manager-client', []);
        } catch (\Throwable) {
            return [];
        }

        return is_array($raw) ? $raw : [];
    }

    /**
     * @param array<string, mixed> $config
     */
    private function stringConfig(array $config, string $key, string $default): string
    {
        $value = $config[$key] ?? $default;

        return is_string($value) && $value !== '' ? $value : $default;
    }

    /**
     * @param array<string, mixed> $config
     */
    private function intConfig(array $config, string $key, int $default): int
    {
        $value = $config[$key] ?? $default;

        return is_numeric($value) ? (int) $value : $default;
    }

    /**
     * @param array<mixed> $arr
     */
    private function intFromArray(array $arr, string $key, int $default): int
    {
        $value = $arr[$key] ?? $default;

        return is_numeric($value) ? (int) $value : $default;
    }

    /**
     * @param array<mixed> $arr
     */
    private function stringFromArray(array $arr, string $key, string $default): string
    {
        $value = $arr[$key] ?? $default;

        return is_string($value) && $value !== '' ? $value : $default;
    }
}
