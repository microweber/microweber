<?php


namespace Microweber\Install;

use Microweber\Module;
use Microweber\Utils\Database as DbUtils;
use Illuminate\Support\Facades\Schema as DbSchema;
use Illuminate\Database\QueryException;

use Cache;

class DbInstaller {
    public function run() {
        Cache::flush();
        $this->createSchema();
        $this->seed();
        Cache::flush();
        mw()->modules->install();
    }

    public function getSystemSchemas() {
        return [
            new Schema\Base,
            new Schema\Comments,
            new Schema\Content,
            new Schema\Form,
            new Schema\Options,
            new Schema\Shop,
            new Schema\Revisions
        ];
    }

    public function createSchema() {
        if (!DbSchema::hasTable('sessions')){
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
            if (!method_exists($data, 'get')){
                break;
            }
            $schemaArray = $data->get();
            if (!is_array($schemaArray)){
                break;
            }
            foreach ($schemaArray as $table => $columns) {
                $builder->build_table($table, $columns);
            }
        }
    }

    public function seed() {
        $exec = $this->getSystemSchemas();
        foreach ($exec as $data) {
            if (method_exists($data, 'up')){
                $data->up();
            }
            if (method_exists($data, 'seed')){
                $data->seed();
            }
        }
    }
}