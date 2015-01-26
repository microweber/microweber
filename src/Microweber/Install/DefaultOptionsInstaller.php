<?php

namespace Microweber\Install;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


use \Option;

class DefaultOptionsInstaller
{

    public function run()
    {

        $this->createDefault();
        return true;
    }

    private function createDefault()
    {

        $existing = DB::table('options')->where('option_key', 'website_title')
            ->where('option_group', 'website')->first();
        if ($existing != false) {
            $option = Option::find($existing->id);
        } else {
            $option = new Option;
        }
        $option->option_key = 'website_title';
        $option->option_group = 'website';
        $option->option_value = 'Microweber';
        $option->is_system = 1;
        $option->save();

        $existing = DB::table('options')->where('option_key', 'enable_comments')
            ->where('option_group', 'comments')->first();
        if ($existing != false) {
            $option = Option::find($existing->id);
        } else {
            $option = new Option;
        }
        $option->option_key = 'enable_comments';
        $option->option_group = 'comments';
        $option->option_value = 'y';
        $option->save();


    }


}