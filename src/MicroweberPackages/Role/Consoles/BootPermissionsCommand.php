<?php

namespace MicroweberPackages\Roles\Consoles;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Permission\Contracts\Permission;

class BootPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:registerPermissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register Permissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$this->info('Start to initialize permissions');
        // Reset cached roles and permissions
        app('cache')->forget('spatie.permission.cache');

        $this->ability('dashboard');

        $this->ability('setting');

        $this->ability('operationlog');

        $this->ability('laravel_logs');

        $this->abilityResources('user');

        $this->abilityResources('role');

        $this->abilityResources('permission');

        $this->call('admin:resetSuperAdmin');

        $this->info('Initialize permissions is complete');
    }

    protected function ability($name)
    {
        $permissionClass = app(Permission::class);

        $permissionClass::findOrCreate($name, 'admin');
    }

    protected function abilityResources($name)
    {
        $name = Str::snake(class_basename($name));

        $abilitys = [
            'view',
            'add',
            'edit',
            'delete'
        ];
        foreach ($abilitys as $ability) {
            $this->ability($ability . '_' . $name);
        }
    }
}
