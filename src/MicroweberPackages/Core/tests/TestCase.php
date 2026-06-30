<?php

namespace MicroweberPackages\Core\tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\App\Managers\PermalinkManager;
use MicroweberPackages\DbInstaller\DbInstaller;
use MicroweberPackages\User\Models\User;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Mime\Part\Multipart\MixedPart;
use Symfony\Component\Mime\Part\TextPart;
use Tests\CreatesApplication;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplication;

    public $parserErrorStrings = ['mw_replace_back', 'tag-comment', 'mw-unprocessed-module-tag', 'parser_'];
    private $sqlite_file = 'phpunit.sqlite';
    public $template_name = '';

    protected function setUp(): void
    {
        ini_set('memory_limit', '4096M');
        ini_set('max_execution_time', '3000');
        parent::setUp();

    }

    protected function setUpTheTestEnvironment(): void
    {


        parent::setUpTheTestEnvironment();
        $installed = \Illuminate\Support\Env::getRepository()->get('MW_IS_INSTALLED');
        if (!defined('MW_UNIT_TEST')) {
            define('MW_UNIT_TEST', true);
        }

        // Also check if the database actually has tables (handles case where
        // MW_IS_INSTALLED=1 but the database is empty or freshly created)
        if ($installed && !defined('MW_UNIT_TEST_DB_VERIFIED')) {
            try {
                $hasTable = \Illuminate\Support\Facades\Schema::hasTable('options');
                if (!$hasTable) {
                    $installed = false;
                }
            } catch (\Exception $e) {
                $installed = false;
            }
            define('MW_UNIT_TEST_DB_VERIFIED', true);
        }

        if (!$installed) {
            $this->install();
        }
    }

    public function install()
    {

        $testing_env_name = 'testing';
        $testEnvironment = $testing_env_name = env('APP_ENV') ? env('APP_ENV') : 'testing';

        $config_folder = __DIR__ . '/../../../../config/';

        if (!is_dir($config_folder)) {
            mkdir($config_folder);
        }

        $mw_file = $config_folder . 'microweber.php';
        $mw_file_database = $config_folder . 'database.php';
        $mw_file = $this->normalizePath($mw_file, false);

        $test_env_from_conf = env('APP_ENV_TEST_FROM_CONFIG');

        // Reuse the existing app instance instead of creating a new one
        $app = $this->app ?? app();

        if (!defined('MW_UNIT_TEST_CONF_FILE_CREATED')) {

            define('MW_UNIT_TEST_CONF_FILE_CREATED', true);

            $this->assertEquals(true, is_dir($config_folder));

            $environment = $app->environment();
            $this->sqlite_file = $this->normalizePath(storage_path() . '/phpunit.' . $environment . '.sqlite', false);

            if (!defined('MW_UNIT_TEST_DB_CLEANED')) {
                if (is_file($this->sqlite_file)) {
                    @unlink($this->sqlite_file);
                }
                define('MW_UNIT_TEST_DB_CLEANED', true);
            }

            $db_driver = env('DB_DRIVER') ? env('DB_DRIVER') : 'sqlite';
            $db_host = env('DB_HOST', '127.0.0.1');
            $db_port = env('DB_PORT', '');

            $db_user = env('DB_USERNAME', 'forge');
            $db_pass = env('DB_PASSWORD', '');
            $db_prefix = env('DB_PREFIX', 'phpunit_test_');
            $db_name = env('DB_DATABASE') ? env('DB_DATABASE') : $this->sqlite_file;

            if ($test_env_from_conf) {
                $dbEngines = Config::get('database.connections');
                $defaultDbEngine = Config::get('database.default');

                if ($defaultDbEngine) {
                    $db_driver = $defaultDbEngine;
                    if (isset($dbEngines[$defaultDbEngine]) and is_array($dbEngines[$defaultDbEngine])) {
                        $config = $dbEngines[$defaultDbEngine];
                        if (isset($config['database'])) {
                            $db_name = $config['database'];
                        }
                        if (isset($config['host'])) {
                            $db_host = $config['host'];
                        }
                        if (isset($config['username'])) {
                            $db_user = $config['username'];
                        }
                        if (isset($config['password'])) {
                            $db_pass = $config['password'];
                        }
                        if (isset($config['prefix'])) {
                            $db_prefix = $config['prefix'];
                        }
                    }
                }
            }

            $template_name = 'Bootstrap';
            $install_params = array(
                '--username' => 'test' . uniqid(),
                '--password' => 'test',
                '--email' => 'test' . uniqid() . '@example.com',
                '--db-driver' => $db_driver,
                '--db-host' => $db_host,
                '--db-username' => $db_user,
                '--db-password' => $db_pass,
                '--db-name' => $db_name,
                '--template' => $template_name,
                '--env' => $environment,
            );

            Config::set('microweber.is_installed', 0);

            $output = new BufferedOutput();
            $output->setDecorated(false);
            $install = Artisan::call('microweber:install', $install_params, $output);
            $outputString = $output->fetch();

            $this->assertEquals(0, $install);
            $this->assertStringContainsString('Environment: testing', $outputString);
            $this->assertStringContainsString('done', $outputString);

            Artisan::call('config:cache');
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            Config::set('microweber.is_installed', 1);
            $is_installed = mw_is_installed();
            $this->assertEquals(1, $is_installed);

            // Persist to the env repository so setUpTheTestEnvironment() and
            // assertPreConditions() see MW_IS_INSTALLED=1 in all subsequent tests
            // without re-running the installer.
            \Illuminate\Support\Env::getRepository()->set('MW_IS_INSTALLED', '1');

//            Config::set('mail.driver', 'array');
//            Config::set('queue.driver', 'sync');
//            Config::set('mail.transport', 'array');
//            Config::set('cache.default', 'array');
        }

        return $app;
    }

    private function normalizePath($path, $slash_it = true)
    {
        $path_original = $path;
        $s = DIRECTORY_SEPARATOR;
        $path = preg_replace('/[\/\\\]/', $s, $path);
        $path = str_replace($s . $s, $s, $path);
        if (strval($path) == '') {
            $path = $path_original;
        }
        if ($slash_it == false) {
            $path = rtrim($path, DIRECTORY_SEPARATOR);
        } else {
            $path .= DIRECTORY_SEPARATOR;
            $path = rtrim($path, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR);
        }
        if (strval(trim($path)) == '' or strval(trim($path)) == '/') {
            $path = $path_original;
        }
        if ($slash_it == false) {
        } else {
            $path = $path . DIRECTORY_SEPARATOR;
            $path = $this->reduce_double_slashes($path);
        }

        return $path;
    }

    private function reduce_double_slashes($str)
    {
        return preg_replace('#([^:])//+#', '\\1/', $str);
    }

    protected function assertPreConditions(): void
    {
        $this->assertEquals('testing', app()->environment());

        if (app()->environment() != 'testing') {
            $this->markTestSkipped('Not in testing environment');
        }

        if (!env('MW_IS_INSTALLED')) {
            $this->install();
        }
        \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(false);
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('options')) {
                DB::table('options')
                    ->where('option_group', 'multilanguage_settings')
                    ->delete();
            }
        } catch (\Exception $e) {
            // Table may not exist yet during initial setup
        }

        app()->bind('permalink_manager', function () {
            return new PermalinkManager();
        });

        if ($this->template_name) {
            save_option('current_template', $this->template_name, 'template');
        }
    }

    public function getEmailDataAsArrayFromObject($email)
    {
        $emailOriginal = $email->getOriginalMessage();
        $body = $emailOriginal->getBody();

        $emailAsArray = [];
        $emailAsArray['body'] = '';
        if ($body instanceof TextPart) {
            $emailAsArray['body'] = $body->getBody();
        }
        if ($body instanceof MixedPart) {
            $emailAsArray['body'] = $body->bodyToString();
            if (isset($emailAsArray['body'])) {
                $emailAsArray['body'] = utf8_encode(quoted_printable_decode($emailAsArray['body']));
            }
        }

        $emailAsArray['subject'] = $emailOriginal->getSubject();
        $emailAsArray['to'] = $emailOriginal->getTo()[0]->getAddress();
        $emailAsArray['from'] = $emailOriginal->getFrom()[0]->getAddress();
        $emailAsArray['replyTo'] = false;

        if (isset($emailOriginal->getReplyTo()[0]) && !empty($emailOriginal->getReplyTo()[0]->getAddress())) {
            $emailAsArray['replyTo'] = $emailOriginal->getReplyTo()[0]->getAddress();
        }

        return $emailAsArray;
    }

    protected function tearDown(): void
    {

        $this->template_name = '';

        /*
         * cycle-N (post-cycle-116 OOM hunt): explicit static-cache reset
         * before Laravel's app tearDown. Several module-singleton caches
         * are intentionally process-shared in production (warm-cache
         * benefit between unrelated requests in long-running PHP-FPM
         * workers) but in tests they accumulate forever — directly
         * causing the ~6MB-per-test leak documented in project memory
         * `project_test_architecture`. Resetting here preserves
         * production semantics and prevents cross-test pollution.
         */
        // CachedBuilder::$_loaded_models_cache_get, TranslateTable::$_getTranslateTranslates,
        // MultilanguagePermalinkManager::$_linkContent and Database\Utils::$get_fields_fields_memory
        // were converted from `static` to instance (`protected`) properties — their holders
        // (a fresh Eloquent builder per query, the `bind` permalink_manager, the per-test
        // database_manager singleton) are recreated per test, so the caches no longer survive
        // the app teardown and need no explicit reset here. (Mirrors the AbstractRepository
        // $_cacheCallbackMemory fix.)
        // phpQuery library accumulates DOM trees in `phpQuery::$documents`
        // across every newDocument() call. Even with the per-call cleanup
        // added to Parser::make_tags / modify_html, any test that creates
        // a phpQuery doc directly leaves it resident.
        if (class_exists(\phpQuery::class, false)) {
            \phpQuery::unloadDocuments();
        }

        // Use Laravel's standard tearDown which properly flushes and destroys the app
        parent::tearDown();

        // Force garbage collection
        gc_collect_cycles();
        if (function_exists('gc_mem_caches')) {
            gc_mem_caches();
        }
    }

    protected function afterRefreshingDatabase()
    {
        // Only run the heavy migration/schema setup once per process.
        if (defined('MW_UNIT_TEST_SCHEMA_CREATED')) {
            return;
        }

        $migrator = app()->mw_migrator->run(app()->migrator->paths());

        $installer = app(DbInstaller::class);
        $installer->logger = $this;
        $installer->createSchema();

        Artisan::call('migrate', ['--force' => true]);

        define('MW_UNIT_TEST_SCHEMA_CREATED', true);
    }

    protected function loginAsAdmin()
    {
        if (app()->environment() != 'testing') {
            return;
        }

        $user = User::where('is_admin', 1)->first();

        if (!$user) {
            $user = new User();
            $user->username = 'test'.uniqid();
            $user->password = 'test'.uniqid();
            $user->email = 'bobi@microweber.com';
            $user->is_admin = 1;
            $user->save();
        }
        Auth::login($user);
    }
}
