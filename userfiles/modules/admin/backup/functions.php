<?php

autoload_add_namespace(__DIR__.'/src/', 'MicroweberPackages\\LegacyBackup\\');


$backup = new \MicroweberPackages\LegacyBackup\Backup();

api_expose_admin('MicroweberPackages\LegacyBackup\Backup\create', $backup->create());
api_expose_admin('MicroweberPackages\LegacyBackup\Backup\create_full', $backup->create_full());
api_expose_admin('MicroweberPackages\LegacyBackup\Backup\cronjob', $backup->cronjob());

api_expose_admin('MicroweberPackages\LegacyBackup\Backup\delete', function($params) use ($backup) {
    return $backup->delete($params);
});

api_expose_admin('MicroweberPackages\LegacyBackup\Backup\download', function($params) use ($backup) {
    return $backup->download($params);
});

api_expose_admin('MicroweberPackages\LegacyBackup\Backup\restore', function($params) use ($backup) {
    return $backup->restore($params);
});

api_expose_admin('MicroweberPackages\LegacyBackup\Backup\move_uploaded_file_to_backup', function($params) use ($backup) {
    return $backup->move_uploaded_file_to_backup($params);
});
