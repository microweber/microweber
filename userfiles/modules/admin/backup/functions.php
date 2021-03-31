<?php
autoload_add_namespace(__DIR__ . '/src/', 'MicroweberPackages\\LegacyBackup\\');

api_expose_admin('MicroweberPackages\LegacyBackup\Backup\create', function ($params) {
    $backup = new \MicroweberPackages\LegacyBackup\Backup();
    return $backup->create($params);
});

api_expose_admin('MicroweberPackages\LegacyBackup\Backup\create_full', function ($params) {
    $backup = new \MicroweberPackages\LegacyBackup\Backup();
    return $backup->create_full($params);
});

api_expose_admin('MicroweberPackages\LegacyBackup\Backup\cronjob', function ($params) {
    $backup = new \MicroweberPackages\LegacyBackup\Backup();
    return $backup->cronjob($params);
});

api_expose_admin('MicroweberPackages\LegacyBackup\Backup\delete', function ($params) {
    $backup = new \MicroweberPackages\LegacyBackup\Backup();
    return $backup->delete($params);
});

api_expose_admin('MicroweberPackages\LegacyBackup\Backup\download', function ($params) {
    $backup = new \MicroweberPackages\LegacyBackup\Backup();
    return $backup->download($params);
});

api_expose_admin('MicroweberPackages\LegacyBackup\Backup\restore', function ($params) {
    $backup = new \MicroweberPackages\LegacyBackup\Backup();
    return $backup->restore($params);
});

api_expose_admin('MicroweberPackages\LegacyBackup\Backup\move_uploaded_file_to_backup', function ($params) {
    $backup = new \MicroweberPackages\LegacyBackup\Backup();
    return $backup->move_uploaded_file_to_backup($params);
});
