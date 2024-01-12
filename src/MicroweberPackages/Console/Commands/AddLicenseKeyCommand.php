<?php

namespace MicroweberPackages\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\App\Models\SystemLicenses;

class AddLicenseKeyCommand extends Command
{
    protected $name = 'microweber:add-license-key';
    protected $description = 'Add license key to Microweber';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'microweber:add-license-key {key}';


    public function handle()
    {
        $licenseLocalKey = $this->argument('key');

        $findSystemLicense = SystemLicenses::where('local_key', $licenseLocalKey)->first();
        if (!$findSystemLicense) {
            $findSystemLicense = new SystemLicenses();
            $findSystemLicense->local_key = $licenseLocalKey;
            $findSystemLicense->save();
            $this->info('License key added successfully!');
        } else {
            $this->info('License key already exists!');
        }


    }
}
