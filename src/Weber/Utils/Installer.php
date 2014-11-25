<?php


namespace Weber\Utils;

use Weber\Module;
use Illuminate\Support\Facades\Schema;

use Cache;

class Installer
{
    function __construct()
    {

    }

    public function run()
    {
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function ($table) {
                $table->string('id')->unique();
                $table->text('payload');
                $table->integer('last_activity');
            });
        }
        Cache::flush();
        mw()->modules->install();
    }


}