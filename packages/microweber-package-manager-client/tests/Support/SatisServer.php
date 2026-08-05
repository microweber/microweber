<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Tests\Support;

use RuntimeException;
use ZipArchive;

/**
 * Builds sample package zips and serves a Satis-compatible packages.json
 * via PHP's built-in server on a free local port.
 */
final class SatisServer
{
    private string $root;
    private string $host = '127.0.0.1';
    private int $port;
    /** @var resource|null */
    private $process = null;

    /** @var list<resource> */
    private array $pipes = [];

    public function __construct(?string $root = null, ?int $port = null)
    {
        $this->root = $root ?? sys_get_temp_dir() . '/mw-pmc-satis-' . uniqid('', true);
        $this->port = $port ?? self::findFreePort();
        if (!is_dir($this->root)) {
            mkdir($this->root . '/dist', 0777, true);
        }
    }

    public function root(): string
    {
        return $this->root;
    }

    public function baseUrl(): string
    {
        return 'http://' . $this->host . ':' . $this->port;
    }

    public function packagesJsonUrl(): string
    {
        return $this->baseUrl() . '/packages.json';
    }

    /**
     * Create zip from a fixture directory and register it in packages.json.
     *
     * @param array<string, mixed> $meta Overrides for package metadata
     * @return array<string, mixed> The package version metadata
     */
    public function addPackageFromDirectory(string $sourceDir, array $meta = []): array
    {
        if (!is_dir($sourceDir)) {
            throw new RuntimeException('Source dir not found: ' . $sourceDir);
        }

        $composerFile = $sourceDir . '/composer.json';
        $composer = [];
        if (is_file($composerFile)) {
            $decoded = json_decode((string) file_get_contents($composerFile), true);
            if (is_array($decoded)) {
                $composer = $decoded;
            }
        }

        $name = (string) ($meta['name'] ?? $composer['name'] ?? 'acme/unknown');
        $version = (string) ($meta['version'] ?? $composer['version'] ?? '1.0.0');
        $type = (string) ($meta['type'] ?? $composer['type'] ?? 'library');
        $targetDir = (string) ($meta['target-dir'] ?? $composer['target-dir'] ?? '');
        $extra = $meta['extra'] ?? $composer['extra'] ?? [];
        if (!is_array($extra)) {
            $extra = [];
        }

        $safeName = str_replace('/', '-', $name);
        $zipName = $safeName . '-' . $version . '.zip';
        $zipPath = $this->root . '/dist/' . $zipName;

        $this->zipDirectory($sourceDir, $zipPath);

        $distUrl = $this->baseUrl() . '/dist/' . $zipName;

        $packageMeta = array_merge($composer, $meta, [
            'name' => $name,
            'version' => $version,
            'type' => $type,
            'target-dir' => $targetDir,
            'extra' => $extra,
            'dist' => [
                'type' => 'zip',
                'url' => $distUrl,
                'reference' => $version,
                'shasum' => hash_file('sha1', $zipPath) ?: '',
            ],
        ]);

        $this->packages[$name][$version] = $packageMeta;

        // Also write per-package JSON (Satis style: packages/vendor/name.json)
        $pkgFile = $this->root . '/packages/' . $name . '.json';
        $pkgDir = dirname($pkgFile);
        if (!is_dir($pkgDir)) {
            mkdir($pkgDir, 0777, true);
        }
        file_put_contents(
            $pkgFile,
            (string) json_encode(['packages' => [$name => $this->packages[$name]]], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        $this->writePackagesJson();

        return $packageMeta;
    }

    /** @var array<string, array<string, array<string, mixed>>> */
    private array $packages = [];

    private function writePackagesJson(): void
    {
        $payload = [
            'packages' => $this->packages,
            'metadata-url' => '/p2/%package%.json',
            'available-packages' => array_keys($this->packages),
        ];
        file_put_contents(
            $this->root . '/packages.json',
            (string) json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    public function start(): void
    {
        $this->writePackagesJson();

        $router = $this->root . '/router.php';
        file_put_contents($router, $this->routerScript());

        // Start in its own process group so stop() can kill the whole tree.
        $cmd = sprintf(
            'exec php -S %s:%d -t %s %s',
            $this->host,
            $this->port,
            escapeshellarg($this->root),
            escapeshellarg($router)
        );

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['file', $this->root . '/server.log', 'a'],
            2 => ['file', $this->root . '/server.log', 'a'],
        ];

        $process = proc_open(
            $cmd,
            $descriptors,
            $this->pipes,
            null,
            null,
            ['bypass_shell' => false]
        );
        if (!is_resource($process)) {
            throw new RuntimeException('Failed to start Satis temp server');
        }
        $this->process = $process;

        // Wait until server responds
        $deadline = microtime(true) + 5.0;
        while (microtime(true) < $deadline) {
            $ctx = stream_context_create(['http' => ['timeout' => 0.5]]);
            $body = @file_get_contents($this->packagesJsonUrl(), false, $ctx);
            if (is_string($body) && str_contains($body, 'packages')) {
                return;
            }
            usleep(50_000);
        }

        throw new RuntimeException('Satis temp server did not become ready at ' . $this->packagesJsonUrl());
    }

    public function stop(): void
    {
        if (is_resource($this->process)) {
            $status = proc_get_status($this->process);
            $pid = is_array($status) && isset($status['pid']) ? (int) $status['pid'] : 0;

            foreach ($this->pipes as $pipe) {
                if (is_resource($pipe)) {
                    fclose($pipe);
                }
            }
            $this->pipes = [];

            // Terminate process group (php -S may leave children)
            if ($pid > 0) {
                if (function_exists('posix_kill')) {
                    @posix_kill(-$pid, 15);
                    usleep(50_000);
                    @posix_kill(-$pid, 9);
                    @posix_kill($pid, 9);
                } else {
                    @exec('kill -9 ' . $pid . ' 2>/dev/null');
                }
            }
            @proc_terminate($this->process, 9);
            @proc_close($this->process);
            $this->process = null;
        }

        // Fallback: free the port
        if (function_exists('exec')) {
            @exec(sprintf(
                "fuser -k %d/tcp 2>/dev/null",
                $this->port
            ));
        }
    }

    public function cleanup(): void
    {
        $this->stop();
        $this->removeTree($this->root);
    }

    private function zipDirectory(string $sourceDir, string $zipPath): void
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Cannot create zip: ' . $zipPath);
        }

        $sourceDir = realpath($sourceDir) ?: $sourceDir;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourceDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            $path = $file->getRealPath();
            if ($path === false) {
                continue;
            }
            $local = substr($path, strlen($sourceDir) + 1);
            $local = str_replace('\\', '/', $local);
            if ($file->isDir()) {
                $zip->addEmptyDir($local);
            } else {
                $zip->addFile($path, $local);
            }
        }
        $zip->close();
    }

    private function routerScript(): string
    {
        return <<<'PHP'
<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
$file = __DIR__ . $uri;
if ($uri !== '/' && is_file($file)) {
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $types = [
        'json' => 'application/json',
        'zip' => 'application/zip',
    ];
    header('Content-Type: ' . ($types[$ext] ?? 'application/octet-stream'));
    readfile($file);
    return true;
}
http_response_code(404);
echo 'Not found';
return true;
PHP;
    }

    private static function findFreePort(): int
    {
        $sock = @stream_socket_server('tcp://127.0.0.1:0');
        if ($sock === false) {
            return random_int(19000, 19999);
        }
        $name = stream_socket_get_name($sock, false);
        fclose($sock);
        if (is_string($name) && str_contains($name, ':')) {
            $parts = explode(':', $name);

            return (int) end($parts);
        }

        return random_int(19000, 19999);
    }

    private function removeTree(string $dir): void
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
