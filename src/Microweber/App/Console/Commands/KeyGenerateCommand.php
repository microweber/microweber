<?php namespace Microweber\App\Console\Commands;


use Illuminate\Foundation\Console\KeyGenerateCommand as KeyGenerate;

class KeyGenerateCommand extends KeyGenerate
{
    /**
     * Set the application key in the environment file.
     *
     * @param  string $key
     * @return void
     */
    protected function setKeyInEnvironmentFile($key)
    {


        $app_file_path = config_path() . DIRECTORY_SEPARATOR . 'app.php';

        @file_put_contents($app_file_path, str_replace(
            'YourSecretKey!!!',
            $key,
            @file_get_contents($app_file_path)
        ));
    }


}
