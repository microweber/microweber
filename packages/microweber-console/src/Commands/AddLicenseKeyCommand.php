<?php

namespace MicroweberPackages\Console\Commands;

use Illuminate\Console\Command;
use MicroweberPackages\SystemLicenses\Models\SystemLicense;

class AddLicenseKeyCommand extends Command
{
    protected $name = 'microweber:add-license-key';
    protected $description = 'Add license key to Microweber';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'microweber:license-add {key}';


    public function handle()
    {
        $licenseLocalKey = $this->argument('key');

        $findSystemLicense = SystemLicense::where('local_key', $licenseLocalKey)->first();
        if (!$findSystemLicense) {
            $findSystemLicense = new SystemLicense();
            $findSystemLicense->local_key = $licenseLocalKey;
            $findSystemLicense->save();
            $this->info('License key added successfully!');
        } else {
            $this->info('License key already exists!');
        }


    }
}
