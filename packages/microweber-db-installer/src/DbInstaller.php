<?php

namespace MicroweberPackages\DbInstaller;

use Illuminate\Support\Facades\Schema as DbSchema;
use Illuminate\Database\QueryException;
use MicroweberPackages\Database\Utils as DbUtils;

/**
 * Microweber database installer.
 *
 * Orchestrates first-install / self-heal of the system schema:
 *  - ensures the sessions + migrations tables exist,
 *  - runs the custom migrator (app()->mw_migrator, from microweber-db-migrator),
 *  - builds each array-defined system table via the database table builder
 *    (MicroweberPackages\Database\Utils::build_table — the single source of
 *    truth for the array->table engine), then seeds.
 *
 * The system schema definitions live in the application
 * (MicroweberPackages\Install\Schema\*) and are consumed here.
 */
class DbInstaller
{
    public $logger = null;

    /**
     * System schema providers to install + seed, injected by the application
     * (see setSystemSchemas). Kept empty by default so this package stays
     * schema-agnostic — it never reaches back into the app for schema data.
     *
     * @var object[]
     */
    protected array $systemSchemas = [];

    /**
     * Inject the system schema providers from the application/installer.
     *
     * @param object[] $schemas
     */
    public function setSystemSchemas(array $schemas): static
    {
        $this->systemSchemas = $schemas;

        return $this;
    }

    public function run()
    {
      //  Cache::flush();
        try {
            $this->createSchema();

        } catch (\Illuminate\Database\QueryException $e) {
            $this->log('Error in database schema: ' . $e->getMessage());
        }

        $this->seed();
      //  Cache::flush();
//        $this->log('Installing modules');
//
//        mw()->module_manager->install();
    }

    private function getMigrationClassNameByFilename($filename)
    {
        $className = '';
        $words = explode('_',  $filename);
        foreach ($words as $word) {
            $word = str_replace('.php', false, $word);
            if (is_numeric($word)) {
                continue;
            }
            if (is_string($word)) {
                $className .= ucfirst($word);
            }
        }
        if (empty($className)) {
            return false;
        }

        return $className;
    }

    public function getVendorSchemas()
    {
        $repos = [];
        foreach (app()->migrator->paths() as $migrationPath) {
            if (is_dir($migrationPath)) {
                if ($dhMigration = opendir($migrationPath)) {
                    while (($migrationFile = readdir($dhMigration)) !== false) {
                        if (strpos( $migrationFile,'.php') !== false) {
                            $migrationClassName = $this->getMigrationClassNameByFilename($migrationFile);
                            if ($migrationClassName) {
                                $migrationFilePath = normalize_path($migrationPath  . DIRECTORY_SEPARATOR . $migrationFile, false);
                              //  $migrationContent = file_get_contents($migrationFilePath);
                                include_once $migrationFilePath;
                                if (!class_exists($migrationClassName)) {
                                    continue;
                                }
                                $instanceMigration = new $migrationClassName;
                                if (method_exists($instanceMigration,'getSchema')) {
                                    $migrationSchema = $instanceMigration->getSchema();
                                    if (!empty($migrationSchema)) {
                                        $repos[] = $instanceMigration;
                                    }
                                }
                                if (method_exists($instanceMigration,'up')) {
                                    $repos[] = $instanceMigration;
                                }
                                if (method_exists($instanceMigration,'get')) {
                                    $repos[] = $instanceMigration;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $repos;
    }

    public function getSystemSchemas()
    {
        // Schemas are injected by the application via setSystemSchemas() — the
        // app-specific definitions live in MicroweberPackages\Install and are
        // wired in (e.g. the container binding in the app's service provider),
        // never pulled from here.
        return $this->systemSchemas;

      //  $all = array_merge($this->systemSchemas, $this->getVendorSchemas());
    }

    public function createSchema()
    {
        if (!DbSchema::hasTable('sessions')) {
            try {
                DbSchema::create('sessions', function ($table) {
                    $table->string('id')->unique();
                    $table->longText('payload');
                    $table->integer('last_activity');
                    $table->integer('user_id')->nullable();
                    $table->string('ip_address', 255)->nullable();
                    $table->text('user_agent')->nullable();
                });
            } catch (QueryException $e) {

            }
        }

        if (DbSchema::hasTable('migrations')) {
            if (DbSchema::hasColumn('migrations', 'hash')) {
                DbSchema::table('migrations', function ($table) {
                    $table->dropColumn('hash');
                });
            }
        }

        if (!DbSchema::hasTable('migrations')) {
            try {
                DbSchema::create('migrations', function ($table) {
                    $table->increments('id');
                    $table->string('migration');
                    $table->integer('batch');
                });
            } catch (QueryException $e) {

            }
        }

        $exec = $this->getSystemSchemas();
        $builder = new DbUtils();
        $schemaArray = array();

        app()->mw_migrator->logger = $this->logger;

        $migrator = app()->mw_migrator->run(app()->migrator->paths());


        foreach ($exec as $data) {
            if (method_exists($data, 'get')) {
                $schemaArray = $data->get();
                if (is_array($schemaArray)) {
                    foreach ($schemaArray as $table => $columns) {
                        $this->log('Setting up table "' . $table . '"');
                        $builder->build_table($table, $columns);
                    }
                }
            }
        }

    }

    public function seed()
    {
        $exec = $this->getSystemSchemas();
        foreach ($exec as $data) {

            if (method_exists($data, 'seed')) {
                $this->log('Seeding ' . get_class($data));

                $data->seed();
            }
        }
    }

    public function log($text)
    {
        if (is_object($this->logger) and method_exists($this->logger, 'log')) {
            $this->logger->log($text);
        }
    }
}
