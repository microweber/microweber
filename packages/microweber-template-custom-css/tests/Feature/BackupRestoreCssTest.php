<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Feature;

use MicroweberPackages\TemplateCustomCss\Services\CssUrlRewriter;
use MicroweberPackages\TemplateCustomCss\Services\CssValidator;
use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;
use MicroweberPackages\TemplateCustomCss\Support\ArrayOptionStore;
use MicroweberPackages\TemplateCustomCss\Tests\TestCase;
use ZipArchive;

/**
 * Ensures live_edit.css path + URL rewrite remain backup/restore compatible.
 */
class BackupRestoreCssTest extends TestCase
{
    public function test_css_file_survives_zip_backup_and_restore_simulation(): void
    {
        $root = sys_get_temp_dir() . '/mw-css-backup-' . uniqid();
        $cssBase = $root . '/userfiles/css';
        $template = 'Bootstrap';
        @mkdir($cssBase . '/' . $template, 0755, true);

        $config = [
            'css_base_path' => $cssBase,
            'css_base_url' => 'http://site.test/userfiles/css',
            'userfiles_url' => 'http://site.test/userfiles/',
            'default_template' => $template,
            'validate_on_save' => true,
            'multisite' => false,
            'live_edit_option_key' => 'template_css',
            'live_edit_option_group_prefix' => 'template_',
            'file_types' => [
                'live_edit' => [
                    'filename' => 'live_edit.css',
                    'validate' => true,
                    'rewrite_urls' => true,
                ],
            ],
        ];

        $store = new ArrayOptionStore();
        $manager = new LiveEditCssManager(
            $config,
            $store,
            new CssValidator(true),
            new CssUrlRewriter($config['userfiles_url'], 'http://site.test/'),
        );

        $marker = '/* backup-marker-' . uniqid() . ' */';
        $css = $marker . "\n.hero { background: url(http://site.test/userfiles/media/hero.png); }\n";
        $manager->saveLiveEditCssContent($css, $template);

        $livePath = $cssBase . '/' . $template . '/live_edit.css';
        $this->assertFileExists($livePath);
        $onDisk = (string) file_get_contents($livePath);
        $this->assertStringContainsString('../../media/hero.png', $onDisk);
        $this->assertStringContainsString($marker, $onDisk);

        // Simulate backup: zip with URL replace (like ZipBatchBackup)
        $zipPath = $root . '/backup.zip';
        $zip = new ZipArchive();
        $this->assertTrue($zip->open($zipPath, ZipArchive::CREATE));
        $rewriter = new CssUrlRewriter($config['userfiles_url'], 'http://site.test/');
        $exportCss = $rewriter->forBackupExport($onDisk, '{SITE_URL}');
        $zip->addFromString('userfiles/css/' . $template . '/live_edit.css', $exportCss);
        $zip->close();
        $this->assertFileExists($zipPath);

        // Simulate restore to a new location
        $restoreRoot = $root . '/restore';
        @mkdir($restoreRoot . '/userfiles/css/' . $template, 0755, true);
        $zip2 = new ZipArchive();
        $this->assertTrue($zip2->open($zipPath));
        $zip2->extractTo($restoreRoot);
        $zip2->close();

        $restoredFile = $restoreRoot . '/userfiles/css/' . $template . '/live_edit.css';
        $this->assertFileExists($restoredFile);
        $restoredContent = (string) file_get_contents($restoredFile);
        $this->assertStringContainsString($marker, $restoredContent);
        $this->assertStringContainsString('../../media/hero.png', $restoredContent);

        // Relative path from userfiles/css/Template must still resolve to userfiles/media
        $this->assertSame(
            realpath($restoreRoot . '/userfiles/css/' . $template),
            realpath(dirname($restoredFile))
        );

        $this->removeTree($root);
    }

    protected function removeTree(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = scandir($dir);
        if ($items === false) {
            return;
        }
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->removeTree($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }
}
