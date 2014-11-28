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
        $this->install_db();
        
        Cache::flush();
        mw()->modules->install();
    }

    private function install_db()
    {
        if (!DbSchema::hasTable('sessions'))
        {
            DbSchema::create('sessions', function ($table) {
                $table->string('id')->unique();
                $table->text('payload');
                $table->integer('last_activity');
            });
        }

        $exec = [
            new Schema\Base,
            new Schema\Comments,
            new Schema\Content,
            new Schema\Form,
            new Schema\Options,
            new Schema\Shop,
            new Schema\Countries
        ];

        foreach ($exec as $data)
        {
            // Creates the schema
            if(method_exists($data, 'get'))
            {
                $schemaArray = $data->get();

                if($schemaArray)
                if(is_array($schemaArray))
                {
                    foreach ($data->get() as $table => $column)
                    {
                        if (!DbSchema::hasTable($table))
                        {
                            DbSchema::create($table, function ($schema) {
                                $schema->increments('id');
                            });
                        }

                        foreach ($column as $name => $type)
                        {
                            DbSchema::table($table, function($schema) use ($name, $type, $table)
                            {
                                if($name == '$index')
                                    return;
                                if (!DbSchema::hasColumn($table, $name))
                                {
                                    $schema->$type($name);
                                }
                            });
                        }
                    }
                }
            }

            // Seeds data
            if(method_exists($data, 'seed'))
            {
                $data->seed();
            }
        }
    }



    

}