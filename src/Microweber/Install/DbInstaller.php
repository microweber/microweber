<?php

namespace Microweber\Install;

use MicroweberPackages\DatabaseManager\Utils as DbUtils;
use Illuminate\Support\Facades\Schema as DbSchema;
use Illuminate\Database\QueryException;
use Cache;

class DbInstaller
{
    public $logger = null;

    public function run()
    {
        Cache::flush();
        try {
            $this->createSchema();

        } catch (\Illuminate\Database\QueryException $e) {
            $this->log('Error in database schema: ' . $e->getMessage());
        }

        $this->seed();
        Cache::flush();
//        $this->log('Installing modules');
//
//        mw()->modules->install();
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

    public function getSystemSchemas()
    {

        $repos = [];
        foreach (app()->migrator->paths() as $migrationPath) {
            if (is_dir($migrationPath)) {
                if ($dhMigration = opendir($migrationPath)) {
                    while (($migrationFile = readdir($dhMigration)) !== false) {
                        if (strpos( $migrationFile,'.php') !== false) {
                            $migrationClassName = $this->getMigrationClassNameByFilename($migrationFile);
                            if ($migrationClassName) {
                                include $migrationPath  . DIRECTORY_SEPARATOR . $migrationFile;
                                $instanceMigration = new $migrationClassName;
                                if (method_exists($instanceMigration,'getSchema')) {
                                    $migrationSchema = $instanceMigration->getSchema();
                                    if (!empty($migrationSchema)) {
                                        $repos[] = $instanceMigration;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $system =  [
            new Schema\Base(),
            new Schema\Comments(),
            new Schema\Content(),
            new Schema\Form(),
            new Schema\Shop(),
            new Schema\Tags(),
            new Schema\JobsQueue(),
            new Schema\PasswordResetsTable(),
            new Schema\Updates(),
        	new Schema\MailSubscribe(),
        	new Schema\MailTemplates()
        ];

        $all = array_merge($system, $repos);

        return $all;
    }

    public function createSchema()
    {
        if (!DbSchema::hasTable('sessions')) {
            try {
                DbSchema::create('sessions', function ($table) {
                    $table->string('id')->unique();
                    $table->longText('payload');
                    $table->integer('last_activity');
                });
            } catch (QueryException $e) {

            }
        }
        $exec = $this->getSystemSchemas();
        $builder = new DbUtils();
        $schemaArray = array();

        foreach ($exec as $data) {
            // Creates the schema

            if (method_exists($data, 'up')) {
                try {
                    $this->log('Setting up schema ' . get_class($data));
                    $data->up();
                } catch (QueryException $e) {


                }
            }

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
