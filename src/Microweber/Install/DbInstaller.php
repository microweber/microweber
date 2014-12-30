<?php


namespace Microweber\Install;

use Microweber\Module;
use Illuminate\Support\Facades\Schema as DbSchema;

use Cache;

class DbInstaller
{
    public function run()
    {
        Cache::flush();
        $this->createSchema();
        $this->seed();

        Cache::flush();
        mw()->modules->install();
    }

    public function getSystemSchemas()
    {
        return [
            new Schema\Base,
            new Schema\Comments,
            new Schema\Content,
            new Schema\Form,
            new Schema\Options,
            new Schema\Shop,
            new Schema\Countries
        ];
    }

    public function createSchema()
    {
        if (!DbSchema::hasTable('sessions')) {
            DbSchema::create('sessions', function ($table) {
                $table->string('id')->unique();
                $table->longText('payload');
                $table->integer('last_activity');
            });
        }
        $exec = $this->getSystemSchemas();

        foreach ($exec as $data) {
            // Creates the schema
            if (!method_exists($data, 'get')) {
                break;
            }

            $schemaArray = $data->get();

            if (!is_array($schemaArray)) {
                break;
            }

            foreach ($data->get() as $table => $column)
            {
                if (!DbSchema::hasTable($table))
                {
                    DbSchema::create($table, function ($schema) {
                        $schema->increments('id');
                    });
                }

                DbSchema::table($table, function ($schema) use ($column, $table)
                {
                    foreach ($column as $name => $type)
                    {
                        if ($name == '$index')
                            return;
                        if (!DbSchema::hasColumn($table, $name)) {
                            $schema->$type($name)->nullable();
                        }
                    }
                });
            }
        }
    }

    public function seed()
    {
        $exec = $this->getSystemSchemas();

        foreach ($exec as $data) {
            if (method_exists($data, 'seed')) {
                $data->seed();
            }
        }
    }
}