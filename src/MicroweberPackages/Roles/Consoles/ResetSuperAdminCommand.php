<?php

namespace MicroweberPackages\Roles\Consoles;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Permission\Contracts\Role;

class ResetSuperAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:resetSuperAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset SuperAdmin';

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
        app(Role::class)::findOrCreate('superadmin', 'admin');
        $user = User::findOrNew(1);
        $user->id = 1;
        $user->email = 'admin@admin.com';
        $user->name = 'administrator';
        $password = Str::random(32);
        $user->password = $password;
        if($user->save())
            $user->assignRole('superadmin');

        $this->info('Reset SuperAdmin is complete.');
        $this->info('email:'.$user->email);
        $this->info('name:'.$user->name);
        $this->info('password:'.$password);
    }
}
