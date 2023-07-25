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
        mkdir_recursive($moduleNamePath . 'src' . DS . 'Http' . DS . 'Livewire' . DS);
        mkdir_recursive($moduleNamePath . 'src' . DS . 'Providers' . DS);
        mkdir_recursive($moduleNamePath . 'src' . DS . 'config' . DS);
        mkdir_recursive($moduleNamePath . 'src' . DS . 'database'.DS.'migrations' . DS);
        mkdir_recursive($moduleNamePath . 'src' . DS . 'resources'.DS.'views' . DS);

        $moduleStubPath = (dirname(dirname(__DIR__))) . DS . 'stubs' . DS . 'microweber-module';


        $replacers = [
            'moduleName' => $moduleName,
            'moduleNamespace' => $moduleNamespace,
            'moduleNameCamelCase' => Str::ucfirst($moduleNameNoSpaces),
            'moduleSlug' => $moduleNameFolder,
            'moduleServiceProvider' => $moduleNamespace .'\\'. Str::ucfirst($moduleNameNoSpaces) . 'ServiceProvider',
        ];

        $controllerLiveEditSettingsName = Str::ucfirst($moduleNameNoSpaces) . 'LiveEditSettingsController';
        StubGenerator::from($moduleStubPath . '/src/Http/Controllers/ModuleNameLiveEditSettingsController.stub',true)
            ->to($moduleNamePath)
            ->as('src/Http/Controllers/'.$controllerLiveEditSettingsName)
            ->ext('php')
            ->withReplacers($replacers)->save();

        $livewireSettingsComponentName = Str::ucfirst($moduleNameNoSpaces) . 'SettingsComponent';
        StubGenerator::from($moduleStubPath . '/src/Http/Livewire/ModuleNameSettingsComponent.stub',true)
            ->to($moduleNamePath)
            ->as('src/Http/Livewire/'.$livewireSettingsComponentName)
            ->ext('php')
            ->withReplacers($replacers)->save();

        $serviceProviderClassName = Str::ucfirst($moduleNameNoSpaces) . 'ServiceProvider';
        StubGenerator::from($moduleStubPath . '/src/Providers/ModuleNameServiceProvider.stub',true)
            ->to($moduleNamePath)
            ->as('src/Providers/'.$serviceProviderClassName)
            ->ext('php')
            ->withReplacers($replacers)
            ->save();

        StubGenerator::from($moduleStubPath . '/index.stub',true)
            ->to($moduleNamePath)
            ->as('index')
            ->ext('php')
            ->save();

        StubGenerator::from($moduleStubPath . '/config.stub',true)
            ->to($moduleNamePath)
            ->as('config')
            ->withReplacers($replacers)
            ->ext('php')
            ->save();

        StubGenerator::from($moduleStubPath . '/icon.stub',true)
            ->to($moduleNamePath)
            ->as('icon')
            ->ext('svg')
            ->save();

        mw_post_update();

    }
}
