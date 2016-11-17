<?php namespace Microweber\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Console\KeyGenerateCommand as KeyGenerate;

class KeyGenerateCommand extends KeyGenerate
{
    /**
     * Set the application key in the environment file.
     *
     * @param  string  $key
     * @return void
     */
    protected function setKeyInEnvironmentFile($key)
    {
//        file_put_contents($this->laravel->environmentFilePath(), str_replace(
//            'APP_KEY='.$this->laravel['config']['app.key'],
//            'APP_KEY='.$key,
//            file_get_contents($this->laravel->environmentFilePath())
//        ));
    }


}
