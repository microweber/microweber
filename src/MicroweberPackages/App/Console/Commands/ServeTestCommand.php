<?php

namespace MicroweberPackages\App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand;
use Symfony\Component\Process\PhpExecutableFinder;

class ServeTestCommand extends ServeCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'serve-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application on the PHP testing server for Dusk and PHPUnit';

    public function handle()
    {
        $status = parent::handle();
        $envArg = $this->option('env')  ;
        if($envArg){
           $_ENV['APP_ENV'] = $envArg;
            putenv('APP_ENV='.$envArg);

        }
        app()->detectEnvironment(function () use ($envArg) {
            return $envArg;
        });
        return $status;
    }
    /**
     * Get the full server command.
     *
     * @return array
     */
    protected function serverCommand()
    {


//        if(isset($_ENV['APP_ENV']) and $_ENV['APP_ENV']){
//            $setEnvCommand = 'export APP_ENV='.$_ENV['APP_ENV'];
//            if (PHP_OS_FAMILY === "Windows") {
//                $setEnvCommand = 'set APP_ENV='.$_ENV['APP_ENV'];
//            }
//            exec($setEnvCommand);
//
//         }

        $command = [
            (new PhpExecutableFinder)->find(false),
            '-d',
            'variables_order=EGPCS',
            '-S',
            $this->host().':'.$this->port(),
            base_path('server.php'),
        ];
        dd($command);
        return $command;
    }

}
