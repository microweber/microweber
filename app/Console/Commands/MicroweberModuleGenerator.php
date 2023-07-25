<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Touhidurabir\StubGenerator\Facades\StubGenerator;

class MicroweberModuleGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'microweber-module-generator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $moduleName = '';

        $this->info('Microweber Module Generator v0.1');
        $this->info('- - - - - - - - - - -');
      //  $moduleName = $this->ask('Enter module name:');

        $moduleName = 'Bojkata Slaveykov';
        $moduleNameFolder = Str::slug($moduleName);
        $moduleNameNoSpaces = str_replace(' ', '', $moduleName);
        $moduleNamePath = userfiles_path() . 'modules' . DS . $moduleNameFolder . DS;

        if (is_dir($moduleNamePath)) {
            $this->error('Module folder already exists: ' . $moduleNamePath);
            return;
        }
        $this->info('Creating module folder: ' . $moduleNamePath);
        mkdir_recursive($moduleNamePath);


        $moduleNamespace = 'MicroweberPackages\\Modules\\' . Str::ucfirst($moduleNameNoSpaces);

        // Making controller
        mkdir_recursive($moduleNamePath . 'src' . DS . 'Http' . DS . 'Controllers' . DS);

        $moduleStubPath = (dirname(dirname(__DIR__))) . DS . 'stubs' . DS . 'microweber-module' . DS;

        $controllerLiveEditSettingsName = Str::ucfirst($moduleNameNoSpaces) . 'LiveEditSettingsController';
        StubGenerator::from($moduleStubPath . '/src/Http/Controllers/ModuleLiveEditSettingsController.stub',true)
            ->to($moduleNamePath)
            ->as('src/Http/Controllers/'.$controllerLiveEditSettingsName)
            ->ext('php')
            ->withReplacers([
                'classNamespace' => $moduleNamespace.'\\Http\\Controllers',
                'class' => $controllerLiveEditSettingsName,
            ])->save();



        StubGenerator::from($moduleStubPath . '/index.stub',true)
            ->to($moduleNamePath)
            ->as('index')
            ->ext('php')
            ->save();

        StubGenerator::from($moduleStubPath . '/config.stub',true)
            ->to($moduleNamePath)
            ->as('config')
            ->withReplacers([
                'moduleName' => $moduleName,
                'moduleNamespace' => $moduleNamespace,
                'moduleServiceProvider' => $moduleNamespace .'\\'. Str::ucfirst($moduleNameNoSpaces) . 'ServiceProvider',
            ])
            ->ext('php')
            ->save();

        StubGenerator::from($moduleStubPath . '/icon.stub',true)
            ->to($moduleNamePath)
            ->as('icon')
            ->ext('svg')
            ->save();
    }
}
