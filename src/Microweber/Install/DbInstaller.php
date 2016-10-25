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
        $this->createSchema();
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
        foreach ($exec as $data) {
            // Creates the schema

            if (method_exists($data, 'up')) {
                $this->log('Setting up schema '.get_class($data));

                $data->up();

                break;
            }

            if (!method_exists($data, 'get')) {
                break;
            }

            $schemaArray = $data->get();
            if (!is_array($schemaArray)) {
                break;
            }
            foreach ($schemaArray as $table => $columns) {
                $this->log('Setting up table "'.$table.'"');

                $builder->build_table($table, $columns);
            }
        }
    }

    public function seed()
    {
        $exec = $this->getSystemSchemas();
        foreach ($exec as $data) {

            if (method_exists($data, 'seed')) {
                $this->log('Seeding '.get_class($data));

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
