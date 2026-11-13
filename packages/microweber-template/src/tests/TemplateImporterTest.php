<?php

namespace MicroweberPackages\Template\tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use MicroweberPackages\Template\Export\TemplateImporter;

/**
 * Covers the security + correctness of importing an uploaded template ZIP: valid
 * archives install as JSON-only template folders, and any archive containing a
 * PHP/executable file or a path-traversal entry is REFUSED without writing
 * anything to a scanned templates directory.
 */
class TemplateImporterTest extends TestCase
{
    private string $work = '';
    private string $dest = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->work = sys_get_temp_dir() . '/mw-tpl-import-' . uniqid();
        $this->dest = $this->work . '/dest';
        @mkdir($this->dest, 0755, true);
    }

    protected function tearDown(): void
    {
        $this->rmrf($this->work);
        parent::tearDown();
    }

    /** Build a zip from [entryName => contents] and return its path. */
    private function makeZip(array $entries, string $file = 'tpl.zip'): string
    {
        $path = $this->work . '/' . $file;
        $zip = new \ZipArchive();
        $zip->open($path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        foreach ($entries as $name => $contents) {
            $zip->addFromString($name, $contents);
        }
        $zip->close();
        return $path;
    }

    private function importer(): TemplateImporter
    {
        return (new TemplateImporter())->setDestRoot($this->dest);
    }

    private function validEntries(string $base = 'MyTheme/'): array
    {
        return [
            $base . 'config.json' => json_encode(['name' => 'MyTheme', 'parent_template' => 'Base']),
            $base . 'content.json' => json_encode(['template' => 'MyTheme', 'pages' => []]),
            $base . 'resources/assets/css/base.css' => 'h1{color:#182433}',
        ];
    }

    #[Test]
    public function it_imports_a_valid_archive_as_a_json_only_template(): void
    {
        $zip = $this->makeZip($this->validEntries());
        $info = $this->importer()->importZip($zip);

        $this->assertSame('MyTheme', $info['name']);
        $this->assertSame('Base', $info['parent_template']);
        $root = $info['dir'];

        $this->assertFileExists($root . '/config.json');
        $this->assertFileExists($root . '/resources/assets/css/base.css');

        // No PHP anywhere in the installed template.
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)) as $f) {
            $this->assertDoesNotMatchRegularExpression('/\.(php|phtml|phar)$/i', $f->getFilename());
        }
    }

    #[Test]
    public function it_refuses_an_archive_containing_a_php_file(): void
    {
        $entries = $this->validEntries();
        $entries['MyTheme/evil.php'] = '<?php system($_GET["c"]); ?>';
        $zip = $this->makeZip($entries, 'evil.zip');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/PHP|executable/i');
        try {
            $this->importer()->importZip($zip);
        } finally {
            // Nothing must have been written to the destination.
            $this->assertSame([], glob($this->dest . '/*') ?: [],
                'A rejected archive must not write any template folder.');
        }
    }

    #[Test]
    public function it_refuses_a_php_file_hidden_under_a_blade_or_alt_extension(): void
    {
        foreach (['MyTheme/views/x.blade.php', 'MyTheme/a.phtml', 'MyTheme/b.phar'] as $bad) {
            $entries = $this->validEntries();
            $entries[$bad] = 'x';
            $zip = $this->makeZip($entries, md5($bad) . '.zip');

            $threw = false;
            try {
                $this->importer()->importZip($zip);
            } catch (\RuntimeException) {
                $threw = true;
            }
            $this->assertTrue($threw, "Archive with {$bad} must be refused.");
        }
    }

    #[Test]
    public function it_refuses_path_traversal_entries(): void
    {
        $entries = $this->validEntries();
        $entries['MyTheme/../../evil.css'] = 'x';
        $zip = $this->makeZip($entries, 'slip.zip');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/unsafe path/i');
        $this->importer()->importZip($zip);
    }

    #[Test]
    public function it_refuses_an_archive_without_a_config_manifest(): void
    {
        $zip = $this->makeZip([
            'MyTheme/content.json' => '{}',
            'MyTheme/resources/assets/css/base.css' => 'h1{}',
        ], 'noconfig.zip');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/not a Microweber template|config\.json/i');
        $this->importer()->importZip($zip);
    }

    #[Test]
    public function it_synthesises_missing_module_and_composer_manifests(): void
    {
        // A valid archive that ships only config.json + assets still needs
        // module.json + composer.json for the scanner — synthesise them.
        $zip = $this->makeZip($this->validEntries());
        $info = $this->importer()->importZip($zip);

        $this->assertFileExists($info['dir'] . '/module.json');
        $this->assertFileExists($info['dir'] . '/composer.json');
        $module = json_decode((string) file_get_contents($info['dir'] . '/module.json'), true);
        $this->assertSame([], $module['providers'], 'Synthesised manifest must register no providers.');
    }

    private function rmrf(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $f) {
            $f->isDir() ? @rmdir($f->getPathname()) : @unlink($f->getPathname());
        }
        @rmdir($dir);
    }
}
