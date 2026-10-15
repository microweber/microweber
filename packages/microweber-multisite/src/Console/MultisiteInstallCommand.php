<?php

declare(strict_types=1);

namespace MicroweberPackages\Multisite\Console;

use Illuminate\Console\Command;
use MicroweberPackages\EnvWriter\EnvWriterService;
use MicroweberPackages\Multisite\MultisiteManager;

/**
 * Scaffold a new site: create the config/{domain}/microweber.php marker that
 * the environment detector keys on, and write that domain's .env.{domain}
 * credentials via the env-writer package. After this the domain boots as its
 * own environment with its own database, cache, session and userfiles tree.
 */
class MultisiteInstallCommand extends Command
{
    protected $signature = 'multisite:install
        {domain : The domain to provision, e.g. domain-a.com}
        {--db-name= : Database name for this site (default: mw_<slug>)}
        {--db-host=127.0.0.1 : Database host}
        {--db-port=3306 : Database port}
        {--db-username=root : Database username}
        {--db-password= : Database password}
        {--db-prefix= : Table prefix}
        {--app-url= : APP_URL for this site (default: https://<domain>)}
        {--force : Overwrite an existing site config/env}';

    protected $description = 'Provision a new multisite domain (config marker + .env.<domain>)';

    public function handle(MultisiteManager $manager, EnvWriterService $envWriter): int
    {
        $domain = MultisiteManager::normalizeHost($this->argument('domain'));
        if ($domain === null) {
            $this->error('Invalid domain: ' . $this->argument('domain'));

            return self::FAILURE;
        }

        $configDir = $manager->configBasePath() . DIRECTORY_SEPARATOR . $domain;
        $markerFile = $configDir . DIRECTORY_SEPARATOR . 'microweber.php';
        $envFile = base_path('.env.' . $domain);

        if ((is_file($markerFile) || is_file($envFile)) && !$this->option('force')) {
            $this->error("Site '{$domain}' already exists. Re-run with --force to overwrite.");

            return self::FAILURE;
        }

        // 1) config/{domain}/microweber.php — the detector's marker. Kept empty
        //    (just a return) so per-domain overrides can be added later by hand.
        if (!is_dir($configDir)) {
            mkdir($configDir, 0775, true);
        }
        file_put_contents(
            $markerFile,
            "<?php\n\n// Multisite marker for {$domain}. Presence of this file makes the\n"
            . "// app boot as the '{$domain}' environment (loads .env.{$domain}).\n\n"
            . "return [];\n"
        );
        $this->info("Created marker: config/{$domain}/microweber.php");

        // 2) .env.{domain} — per-site credentials, written via the env-writer package.
        $slug = MultisiteManager::slug($domain);
        $values = [
            'APP_ENV' => $domain,
            'APP_URL' => $this->option('app-url') ?: ('https://' . $domain),
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => $this->option('db-host'),
            'DB_PORT' => $this->option('db-port'),
            'DB_DATABASE' => $this->option('db-name') ?: ('mw_' . $slug),
            'DB_USERNAME' => $this->option('db-username'),
            'DB_PASSWORD' => (string) $this->option('db-password'),
            'CACHE_PREFIX' => $manager->cachePrefix($domain),
            'SESSION_COOKIE' => $manager->sessionCookie($domain),
        ];
        if ($this->option('db-prefix')) {
            $values['DB_PREFIX'] = $this->option('db-prefix');
        }

        if (!$envWriter->save($values, $envFile)) {
            $this->error('Failed to write ' . $envFile);

            return self::FAILURE;
        }
        $this->info('Wrote env: .env.' . $domain);

        // 3) pre-create the per-site storage trees so the first request is clean.
        $manager->ensureDirectories($domain);

        $this->newLine();
        $this->line("Site <info>{$domain}</info> provisioned:");
        $this->table(['Setting', 'Value'], [
            ['APP_URL', $values['APP_URL']],
            ['Userfiles folder', $manager->userfilesFolder($domain)],
            ['Cache prefix', $manager->cachePrefix($domain)],
            ['Cache path', $manager->cachePath($domain)],
            ['Session cookie', $manager->sessionCookie($domain)],
            ['Session path', $manager->sessionPath($domain)],
            ['Database', $values['DB_DATABASE']],
        ]);
        $this->newLine();
        $this->line('Next: point the domain DNS at this server, create the database, then visit the domain to run its install flow.');

        return self::SUCCESS;
    }
}
