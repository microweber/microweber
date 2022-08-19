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
            $config_dir = __DIR__ . '/../../../config';
            if(!is_dir($config_dir)){
                // do nothing
               return;
            }
            foreach ($to_copy as $file) {
                $target = normalize_path($config_dir . '/' . no_ext($file) . '.php', false);
                if (is_link($target)) {
                    continue;
                }
                if (!is_file($target)) {
                    $source = normalize_path($dir . '/' . $file, false);
                    copy($source, $target);
                }
            }
        }

    }

}
