<?php

namespace MicroweberPackages\App\Repositories;

class ProviderRepository extends \Illuminate\Foundation\ProviderRepository
{
    public function writeManifest($manifest)
    {
        if (! is_writable($dirname = dirname($this->manifestPath))) {
            throw new \Exception("The {$dirname} directory must be present and writable.");
        }
dd('ProviderRepository writeManifest',121212);
        @file_put_contents($this->manifestPath, '<?php return '.var_export($manifest, true).';');
//        $this->files->replace(
//            $this->manifestPath, '<?php return '.var_export($manifest, true).';'
//        );

        return array_merge(['when' => []], $manifest);
    }
}
