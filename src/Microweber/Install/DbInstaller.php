<?php

namespace Microweber\Install;

use Microweber\Providers\Database\Utils as DbUtils;
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
        $this->log('Installing modules');

        mw()->modules->install();
    }

    public function getSystemSchemas()
    {
        return [
            new Schema\Base(),
            new Schema\Comments(),
            new Schema\Content(),
            new Schema\Form(),
            new Schema\Options(),
            new Schema\Shop(),
            new Schema\Tags(),
            new Schema\JobsQueue(),
            new Schema\PasswordResetsTable(),
            new Schema\Updates(),
        ];
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
                $this->log('Setting up schema ' . get_class($data));
                $data->up();
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
