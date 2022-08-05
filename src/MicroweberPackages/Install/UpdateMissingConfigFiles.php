<?php

namespace MicroweberPackages\Install;

class UpdateMissingConfigFiles
{
    public function copyMissingConfigStubs()
    {
        $dir = __DIR__ . '/resources/stubs/config';

        $files = scandir($dir);
        $to_copy = array();
        if ($files) {
            foreach ($files as $file) {
                $ext = get_file_extension($file);
                if ($ext == 'stub') {
                    $to_copy[] = $file;
                }
            }
        }
        if ($files) {
            $config_dir = config_path();
            foreach ($to_copy as $file) {
                $target = normalize_path($config_dir . '/' . basename($file) . '.php', false);
                if (!is_file($target)) {
                    $source = normalize_path($dir . '/' . $file, false);
                    copy($source, $target);
                }
            }
        }

    }

}
