<?php


namespace Microweber\Utils;

use Microweber\Module;
use Illuminate\Support\Facades\Schema;

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
        if (!Schema::hasTable('sessions'))
        {
            Schema::create('sessions', function ($table) {
                $table->string('id')->unique();
                $table->text('payload');
                $table->integer('last_activity');
            });
        }

        $exec = [
            new \Microweber\Install\Base,
            new \Microweber\Install\Comments,
            new \Microweber\Install\Content,
            new \Microweber\Install\Form,
            new \Microweber\Install\Options,
            new \Microweber\Install\Shop
        ];

        $tc = 0;
        foreach ($exec as $data)
        {
            foreach ($data->get() as $table => $column)
            {
                if (!Schema::hasTable($table))
                {
                    Schema::create($table, function ($schema) {
                        $schema->increments('id');
                    });
                }

                foreach ($column as $name => $type)
                {
                    Schema::table($table, function($schema) use ($name, $type, $table)
                    {
                        if($name == '$index')
                            return;
                        if (!Schema::hasColumn($table, $name))
                        {
                            $schema->$type($name);
                        }
                    });
                }
                $tc++;
            }
        }
        var_dump($tc, 'tables creted DSADSA');
    }

    

}