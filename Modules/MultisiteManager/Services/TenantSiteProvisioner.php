<?php

declare(strict_types=1);

namespace Modules\MultisiteManager\Services;

use MicroweberPackages\EnvWriter\EnvWriterService;
use MicroweberPackages\Multisite\MultisiteManager;
use Symfony\Component\Process\Process;

/**
 * Provisions and manages env-based tenant subsites.
 *
 * A subsite is, exactly as Microweber already boots it, a domain with a
 * config/<domain>/microweber.php marker and a .env.<domain> credential file.
 * This service is the admin-facing mirror of the `multisite:install` command:
 * it lists the main site + its subsites, and creates / updates / removes them,
 * optionally creating the MySQL database and running the per-site installer.
 *
 * The filesystem (markers + env files) is the single source of truth — the same
 * files the framework reads at boot — so there is no central registry to drift.
 */
class TenantSiteProvisioner
{
    /** Env var that can name the main website's primary domain (display only). */
    public const MAIN_DOMAIN_ENV = 'MW_MULTISITE_MAIN_DOMAIN';

    public function __construct(
        protected MultisiteManager $manager,
        protected EnvWriterService $envWriter,
    ) {
    }

    // ── Listing ───────────────────────────────────────────────────────────────

    /** The main website (the default environment this install boots as). */
    public function mainSite(): array
    {
        $host = $this->readEnv(self::MAIN_DOMAIN_ENV);
        if (!$host) {
            $url = (string) config('app.url');
            $host = $url ? (parse_url($url, PHP_URL_HOST) ?: $url) : 'main';
        }

        $conn = (string) config('database.default');

        return [
            'domain' => $host,
            'is_main' => true,
            'app_url' => (string) config('app.url'),
            'db_connection' => $conn,
            'db_database' => (string) config("database.connections.{$conn}.database"),
            'db_host' => (string) config("database.connections.{$conn}.host"),
            'userfiles' => $this->manager->userfilesFolder(null),
            'marker' => null,
            'env_file' => null,
            'installed' => true,
        ];
    }

    /** Every configured subsite, newest-marker-first is not meaningful so sorted by domain. */
    public function subsites(): array
    {
        $out = [];
        foreach ($this->manager->sites() as $domain) {
            $out[] = $this->describe($domain);
        }

        return $out;
    }

    /** Main site first, then each subsite — the rows the admin table renders. */
    public function all(): array
    {
        return array_merge([$this->mainSite()], $this->subsites());
    }

    /** Describe a single subsite from its marker + .env.<domain>. */
    public function describe(string $domain): array
    {
        $domain = (string) MultisiteManager::normalizeHost($domain);
        $envFile = $this->manager->siteEnvFile($domain);
        $values = is_file($envFile) ? $this->envWriter->read($envFile) : [];

        return [
            'domain' => $domain,
            'is_main' => false,
            'app_url' => $values['APP_URL'] ?? ('https://' . $domain),
            'db_connection' => $values['DB_CONNECTION'] ?? 'mysql',
            'db_database' => $values['DB_DATABASE'] ?? '',
            'db_host' => $values['DB_HOST'] ?? '127.0.0.1',
            'db_port' => $values['DB_PORT'] ?? '3306',
            'db_username' => $values['DB_USERNAME'] ?? '',
            'userfiles' => $this->manager->userfilesFolder($domain),
            'cache_prefix' => $this->manager->cachePrefix($domain),
            'session_cookie' => $this->manager->sessionCookie($domain),
            'marker' => $this->markerFile($domain),
            'env_file' => $envFile,
            'installed' => $this->looksInstalled($domain, $values),
        ];
    }

    public function exists(string $domain): bool
    {
        $domain = MultisiteManager::normalizeHost($domain);
        if ($domain === null) {
            return false;
        }

        return is_file($this->markerFile($domain)) || is_file($this->manager->siteEnvFile($domain));
    }

    // ── Create ────────────────────────────────────────────────────────────────

    /**
     * Provision a new subsite: marker + .env.<domain>, then (optionally) create
     * the database and run the per-site installer.
     *
     * @param  array  $data  domain, db_host, db_port, db_database, db_username,
     *                       db_password, db_prefix, app_url, create_database,
     *                       run_install, admin_email, admin_username,
     *                       admin_password, template, default_content, language
     * @return array{ok:bool,domain:?string,messages:array<int,string>}
     */
    public function create(array $data): array
    {
        $messages = [];
        $domain = MultisiteManager::normalizeHost($data['domain'] ?? '');
        if ($domain === null) {
            return ['ok' => false, 'domain' => null, 'messages' => ['Invalid domain: ' . ($data['domain'] ?? '')]];
        }
        if ($this->exists($domain) && empty($data['force'])) {
            return ['ok' => false, 'domain' => $domain, 'messages' => ["Site '{$domain}' already exists."]];
        }

        $slug = MultisiteManager::slug($domain);
        $db = [
            'host' => trim((string) ($data['db_host'] ?? '127.0.0.1')) ?: '127.0.0.1',
            'port' => trim((string) ($data['db_port'] ?? '3306')) ?: '3306',
            'name' => trim((string) ($data['db_database'] ?? '')) ?: ('mw_' . $slug),
            'username' => trim((string) ($data['db_username'] ?? 'root')),
            'password' => (string) ($data['db_password'] ?? ''),
            'prefix' => trim((string) ($data['db_prefix'] ?? '')),
        ];
        $appUrl = trim((string) ($data['app_url'] ?? '')) ?: ('https://' . $domain);

        // 1) Marker — presence makes the app boot as this domain's environment.
        $marker = $this->markerFile($domain);
        if (!is_dir(dirname($marker))) {
            @mkdir(dirname($marker), 0775, true);
        }
        file_put_contents(
            $marker,
            "<?php\n\n// Multisite marker for {$domain}. Presence of this file makes the\n"
            . "// app boot as the '{$domain}' environment (loads .env.{$domain}).\n\n"
            . "return [];\n"
        );
        $messages[] = "Created marker config/{$domain}/microweber.php";

        // 2) .env.<domain> — per-site credentials.
        $values = [
            'APP_ENV' => $domain,
            'APP_URL' => $appUrl,
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => $db['host'],
            'DB_PORT' => $db['port'],
            'DB_DATABASE' => $db['name'],
            'DB_USERNAME' => $db['username'],
            'DB_PASSWORD' => $db['password'],
            'CACHE_PREFIX' => $this->manager->cachePrefix($domain),
            'SESSION_COOKIE' => $this->manager->sessionCookie($domain),
        ];
        if ($db['prefix'] !== '') {
            $values['DB_PREFIX'] = $db['prefix'];
        }
        $envFile = $this->manager->siteEnvFile($domain);
        if (!is_dir(dirname($envFile))) {
            @mkdir(dirname($envFile), 0775, true);
        }
        if (!$this->envWriter->save($values, $envFile)) {
            return ['ok' => false, 'domain' => $domain, 'messages' => array_merge($messages, ['Failed to write ' . $envFile])];
        }
        $messages[] = 'Wrote .env.' . $domain;

        // 3) Per-site storage trees (cache/session) so the first request is clean.
        $this->manager->ensureDirectories($domain);

        $ok = true;

        // 4) Create the database (best effort, opt-in).
        if (!empty($data['create_database'])) {
            $res = $this->createDatabase($db);
            $messages[] = $res['message'];
            $ok = $ok && $res['ok'];
        }

        // 5) Run the per-site installer as its own APP_ENV=<domain> process.
        if ($ok && !empty($data['run_install'])) {
            $res = $this->runInstall($domain, $db, $appUrl, $data);
            $messages[] = $res['message'];
            $ok = $ok && $res['ok'];
        }

        return ['ok' => $ok, 'domain' => $domain, 'messages' => $messages];
    }

    // ── Update ────────────────────────────────────────────────────────────────

    /** Rewrite a subsite's .env.<domain> credentials in place. */
    public function update(string $domain, array $data): array
    {
        $domain = MultisiteManager::normalizeHost($domain);
        if ($domain === null || !$this->exists($domain)) {
            return ['ok' => false, 'domain' => $domain, 'messages' => ['Unknown site.']];
        }

        $envFile = $this->manager->siteEnvFile($domain);
        $values = is_file($envFile) ? $this->envWriter->read($envFile) : [];

        $map = [
            'app_url' => 'APP_URL',
            'db_host' => 'DB_HOST',
            'db_port' => 'DB_PORT',
            'db_database' => 'DB_DATABASE',
            'db_username' => 'DB_USERNAME',
            'db_password' => 'DB_PASSWORD',
            'db_prefix' => 'DB_PREFIX',
        ];
        foreach ($map as $in => $key) {
            if (array_key_exists($in, $data) && $data[$in] !== null) {
                $values[$key] = (string) $data[$in];
            }
        }
        // Keep the identity keys consistent with the domain.
        $values['APP_ENV'] = $domain;

        if (!$this->envWriter->save($values, $envFile)) {
            return ['ok' => false, 'domain' => $domain, 'messages' => ['Failed to write ' . $envFile]];
        }

        return ['ok' => true, 'domain' => $domain, 'messages' => ['Updated .env.' . $domain]];
    }

    // ── Delete ────────────────────────────────────────────────────────────────

    /**
     * Remove a subsite. Always drops the marker + .env.<domain>. With
     * drop_database=true also DROPs its MySQL schema; with purge_storage=true
     * removes its per-site cache/session/userfiles trees.
     */
    public function delete(string $domain, array $opts = []): array
    {
        $domain = MultisiteManager::normalizeHost($domain);
        if ($domain === null) {
            return ['ok' => false, 'domain' => null, 'messages' => ['Invalid domain.']];
        }

        $messages = [];
        $info = $this->describe($domain);

        if (!empty($opts['drop_database']) && $info['db_database'] !== '') {
            $res = $this->dropDatabase([
                'host' => $info['db_host'],
                'port' => $info['db_port'],
                'name' => $info['db_database'],
                'username' => $info['db_username'],
                'password' => $this->readSiteEnvValue($domain, 'DB_PASSWORD'),
            ]);
            $messages[] = $res['message'];
        }

        $env = $this->manager->siteEnvFile($domain);
        if (is_file($env)) {
            @unlink($env);
            $messages[] = 'Removed .env.' . $domain;
        }
        $markerDir = dirname($this->markerFile($domain));
        if (is_dir($markerDir)) {
            $this->rrmdir($markerDir);
            $messages[] = "Removed config/{$domain}/";
        }

        if (!empty($opts['purge_storage'])) {
            foreach ([$this->manager->cachePath($domain), $this->manager->sessionPath($domain)] as $dir) {
                if (is_dir($dir)) {
                    $this->rrmdir($dir);
                }
            }
            $messages[] = 'Purged per-site storage.';
        }

        return ['ok' => true, 'domain' => $domain, 'messages' => $messages];
    }

    // ── Database helpers ──────────────────────────────────────────────────────

    /** CREATE DATABASE IF NOT EXISTS via a raw PDO connection. */
    public function createDatabase(array $db): array
    {
        $name = $db['name'] ?? '';
        if (!preg_match('/^[A-Za-z0-9_]+$/', (string) $name)) {
            return ['ok' => false, 'message' => "Refused to create database with unsafe name '{$name}'."];
        }

        try {
            $pdo = $this->rawPdo($db);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            return ['ok' => true, 'message' => "Database `{$name}` ready."];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => 'Could not create database: ' . $e->getMessage()];
        }
    }

    public function dropDatabase(array $db): array
    {
        $name = $db['name'] ?? '';
        if (!preg_match('/^[A-Za-z0-9_]+$/', (string) $name)) {
            return ['ok' => false, 'message' => "Refused to drop database with unsafe name '{$name}'."];
        }
        try {
            $pdo = $this->rawPdo($db);
            $pdo->exec("DROP DATABASE IF EXISTS `{$name}`");

            return ['ok' => true, 'message' => "Dropped database `{$name}`."];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => 'Could not drop database: ' . $e->getMessage()];
        }
    }

    protected function rawPdo(array $db): \PDO
    {
        $host = $db['host'] ?? '127.0.0.1';
        $port = $db['port'] ?? '3306';
        $dsn = "mysql:host={$host};port={$port}";

        return new \PDO($dsn, (string) ($db['username'] ?? 'root'), (string) ($db['password'] ?? ''), [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_TIMEOUT => 10,
        ]);
    }

    // ── Install ───────────────────────────────────────────────────────────────

    /**
     * Run `microweber:install` for the subsite in its own APP_ENV=<domain>
     * process, so it boots against that site's .env/DB and installs there
     * without disturbing this (main-site) request's connection.
     */
    public function runInstall(string $domain, array $db, string $appUrl, array $data): array
    {
        $artisan = base_path('artisan');
        if (!is_file($artisan)) {
            return ['ok' => false, 'message' => 'artisan not found; cannot run install.'];
        }

        $args = [
            PHP_BINARY, $artisan, 'microweber:install',
            '--db-driver=mysql',
            '--db-host=' . ($db['host'] ?? '127.0.0.1'),
            '--db-port=' . ($db['port'] ?? '3306'),
            '--db-name=' . ($db['name'] ?? ''),
            '--db-username=' . ($db['username'] ?? 'root'),
            '--db-password=' . ($db['password'] ?? ''),
            '--app-url=' . $appUrl,
            '--email=' . trim((string) ($data['admin_email'] ?? 'admin@admin.com')),
            '--username=' . trim((string) ($data['admin_username'] ?? 'admin')),
            '--password=' . trim((string) ($data['admin_password'] ?? 'admin')),
            '--default-content=' . (!empty($data['default_content']) ? '1' : '0'),
            '--language=' . (trim((string) ($data['language'] ?? '')) ?: 'en'),
        ];
        if (($db['prefix'] ?? '') !== '') {
            $args[] = '--db-prefix=' . $db['prefix'];
        }
        if (!empty($data['template'])) {
            $args[] = '--template=' . $data['template'];
        }

        try {
            $process = new Process($args, base_path(), ['APP_ENV' => $domain], null, 900);
            $process->run();

            if (!$process->isSuccessful()) {
                $tail = trim(substr($process->getErrorOutput() ?: $process->getOutput(), -800));

                return ['ok' => false, 'message' => "Install failed for {$domain}: " . $tail];
            }

            return ['ok' => true, 'message' => "Installed {$domain}."];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => 'Install process error: ' . $e->getMessage()];
        }
    }

    // ── Small helpers ─────────────────────────────────────────────────────────

    protected function markerFile(string $domain): string
    {
        return $this->manager->configBasePath() . DIRECTORY_SEPARATOR . $domain . DIRECTORY_SEPARATOR . 'microweber.php';
    }

    /** Best-effort "does this subsite's DB look installed" (presence of a users-ish table). */
    protected function looksInstalled(string $domain, array $values): bool
    {
        $name = $values['DB_DATABASE'] ?? '';
        if ($name === '') {
            return false;
        }
        try {
            $pdo = $this->rawPdo([
                'host' => $values['DB_HOST'] ?? '127.0.0.1',
                'port' => $values['DB_PORT'] ?? '3306',
                'username' => $values['DB_USERNAME'] ?? 'root',
                'password' => $values['DB_PASSWORD'] ?? '',
            ]);
            $stmt = $pdo->query(
                'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '
                . $pdo->quote($name)
            );

            return ((int) $stmt->fetchColumn()) > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }

    protected function readSiteEnvValue(string $domain, string $key): string
    {
        $envFile = $this->manager->siteEnvFile($domain);
        $values = is_file($envFile) ? $this->envWriter->read($envFile) : [];

        return (string) ($values[$key] ?? '');
    }

    protected function readEnv(string $key): ?string
    {
        $value = getenv($key);
        if ($value === false || $value === '') {
            $value = $_ENV[$key] ?? $_SERVER[$key] ?? null;
        }

        return (is_string($value) && $value !== '') ? $value : null;
    }

    protected function rrmdir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $entry;
            is_dir($path) ? $this->rrmdir($path) : @unlink($path);
        }
        @rmdir($dir);
    }
}
