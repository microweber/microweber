<?php

declare(strict_types=1);

namespace MicroweberPackages\Package\Tests\Feature;

use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use MicroweberPackages\Package\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;

/**
 * Asserts that every known packages/* service provider uses the new
 * MicroweberPackageServiceProvider loader (extends the abstract base).
 */
class PackagesUseMicroweberLoaderTest extends TestCase
{
    /**
     * @return array<string, array{0: class-string}>
     */
    public static function packageProviderMap(): array
    {
        return [
            'class-loader' => [\MicroweberPackages\ClassLoader\ClassLoaderServiceProvider::class],
            'filesystem' => [\MicroweberPackages\Filesystem\FilesystemServiceProvider::class],
            'format' => [\MicroweberPackages\Format\FormatServiceProvider::class],
            'security' => [\MicroweberPackages\Security\SecurityServiceProvider::class],
            'http' => [\MicroweberPackages\Http\HttpServiceProvider::class],
            'taggable-file-cache' => [\MicroweberPackages\TaggableFileCache\TaggableFileCacheServiceProvider::class],
            'env-writer' => [\MicroweberPackages\EnvWriter\EnvWriterServiceProvider::class],
            'blade-cache' => [\MicroweberPackages\BladeCache\BladeCacheServiceProvider::class],
            'repository' => [\MicroweberPackages\Repository\Providers\RepositoryServiceProvider::class],
            'searchable' => [\MicroweberPackages\Searchable\SearchableServiceProvider::class],
            'url' => [\MicroweberPackages\Url\Providers\UrlServiceProvider::class],
            'database' => [\MicroweberPackages\Database\DatabaseManagerServiceProvider::class],
            'db-migrator' => [\MicroweberPackages\DbMigrator\DbMigratorServiceProvider::class],
            'db-installer' => [\MicroweberPackages\DbInstaller\DbInstallerServiceProvider::class],
            'db-export' => [\MicroweberPackages\DbExport\DbExportServiceProvider::class],
            'event-manager' => [\MicroweberPackages\Event\EventManagerServiceProvider::class],
            'config' => [\MicroweberPackages\Config\ConfigServiceProvider::class],
            'file-uploader' => [\MicroweberPackages\FileUploader\FileUploaderServiceProvider::class],
            'filament-registry' => [\MicroweberPackages\FilamentRegistry\FilamentRegistryServiceProvider::class],
            'filament-modal-teleport' => [\MicroweberPackages\FilamentModalTeleport\ModalTeleportServiceProvider::class],
            'phpquery' => [\MicroweberPackages\PhpQuery\Providers\PhpQueryServiceProvider::class],
            'ai-tools' => [\MicroweberPackages\AiTools\Providers\AiToolsServiceProvider::class],
            'view' => [\MicroweberPackages\View\ViewServiceProvider::class],
            'zip' => [\MicroweberPackages\Zip\ZipServiceProvider::class],
            'thumbnailer' => [\MicroweberPackages\Thumbnailer\ThumbnailerServiceProvider::class],
            'cdn-sync' => [\MicroweberPackages\CdnSync\CdnSyncServiceProvider::class],
            'content-field' => [\MicroweberPackages\ContentField\ContentFieldServiceProvider::class],
            'svg-icons' => [\MicroweberPackages\SvgIcons\SvgIconsServiceProvider::class],
            'system-licenses' => [\MicroweberPackages\SystemLicenses\SystemLicensesServiceProvider::class],
            'mail-sender' => [\MicroweberPackages\MailSender\MailSenderServiceProvider::class],
            'minifier' => [\MicroweberPackages\Minifier\MinifierServiceProvider::class],
            'media-pixum' => [\MicroweberPackages\MediaPixum\MediaPixumServiceProvider::class],
            'media-thumbnail' => [\MicroweberPackages\MediaThumbnail\MediaThumbnailServiceProvider::class],
            'image-optimization' => [\MicroweberPackages\ImageOptimization\ImageOptimizationServiceProvider::class],
            'template-fonts' => [\MicroweberPackages\TemplateFonts\TemplateFontsServiceProvider::class],
            'template-custom-css' => [\MicroweberPackages\TemplateCustomCss\TemplateCustomCssServiceProvider::class],
            'queue' => [\MicroweberPackages\Queue\Providers\QueueServiceProvider::class],
            'notification' => [\MicroweberPackages\Notification\Providers\NotificationServiceProvider::class],
            'package-manager-client' => [\MicroweberPackages\PackageManagerClient\PackageManagerClientServiceProvider::class],
            'fortify' => [\MicroweberPackages\Fortify\FortifyServiceProvider::class],
            'passport' => [\MicroweberPackages\Passport\Providers\MicroweberPassportServiceProvider::class],
            'social-login' => [\MicroweberPackages\SocialLogin\Providers\SocialLoginServiceProvider::class],
            'disposable-email-checker' => [\MicroweberPackages\DisposableEmailChecker\Providers\DisposableEmailCheckerServiceProvider::class],
            'frontend-assets' => [\MicroweberPackages\FrontendAssets\MicroweberFrontendAssetsServiceProvider::class],
            'frontend-assets-libs' => [\MicroweberPackages\FrontendAssetsLibs\MicroweberFrontendAssetsLibsServiceProvider::class],
            'filament-theme' => [\MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentThemeServiceProvider::class],
        ];
    }

    /**
     * @param  class-string  $providerClass
     */
    #[Test]
    #[DataProvider('packageProviderMap')]
    public function provider_extends_microweber_package_service_provider(string $providerClass): void
    {
        if (! class_exists($providerClass)) {
            $this->markTestSkipped("{$providerClass} is not autoloadable in this environment");
        }

        $ref = new ReflectionClass($providerClass);
        $this->assertTrue(
            $ref->isSubclassOf(MicroweberPackageServiceProvider::class),
            "{$providerClass} must extend " . MicroweberPackageServiceProvider::class
        );
        $this->assertFalse(
            $ref->isAbstract(),
            "{$providerClass} must be a concrete provider"
        );
    }
}
