<?php

namespace MicroweberPackages\Console\Commands;

use Illuminate\Console\Command;

class UpdateCommand extends Command
{
    protected $name = 'microweber:update';
    protected $description = 'Update Microweber';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'microweber:update {--branch=} {--confirm=}';


    public function handle()
    {

        if (!class_exists('MicroweberPackages\Modules\StandaloneUpdater\StandaloneUpdaterServiceProvider', false)) {
            $this->error('Standalone updater module is not installed');
            return;
        }

        if (mw_is_installed()) {
            $confirm = $this->option('confirm');
            if (!$confirm) {
                if (!$this->confirm('Do you wish to continue with the update? (yes|no)[no]', true)) {
                    $this->info("Process terminated by user");
                    return;
                }
            }


            $branch = $this->option('branch');
            if (!$branch) {
                $branch = 'master';
            }

            $contoller = new \MicroweberPackages\Modules\StandaloneUpdater\Http\Controllers\StandaloneUpdaterController;

            try {
                $update = $contoller->updateFromCli($branch);
            } catch (\Exception $e) {
                $this->error('Error: ' . $e->getMessage());
                return;
            }
            chdir(base_path());

            $this->info('Update is complete from branch: ' . $branch);

        }

    }
}
