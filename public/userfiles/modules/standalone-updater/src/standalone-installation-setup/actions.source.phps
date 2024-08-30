<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define("INI_SYSTEM_CHECK_DISABLED", ini_get('disable_functions'));

if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'ini_set')) {
    ini_set("memory_limit", "512M");
    ini_set("set_time_limit", 0);
}

if (!strstr(INI_SYSTEM_CHECK_DISABLED, 'date_default_timezone_set')) {
    date_default_timezone_set('America/Los_Angeles');
}

include(__DIR__ . '/Unzip.php');
include(__DIR__ . '/StandaloneUpdateExecutor.php');
include(__DIR__ . '/StandaloneUpdateReplacer.php');



if (isset($_REQUEST['format']) && $_REQUEST['format'] == "json") {

    $json = [];
    header('Content-Type: application/json');

    if (isset($_GET['startSession']) && $_GET['startSession'] == 1) {
        $update = new StandaloneUpdateExecutor();
        $update->setInstallVersion($_GET['installVersion']);
        $json['start'] = $update->startSession();
    }

    if (isset($_GET['startUpdating']) && $_GET['startUpdating'] == 1) {
        $update = new StandaloneUpdateExecutor();
        $json['updating'] = $update->startUpdating();
    }

    if (isset($_GET['unzippApp']) && $_GET['unzippApp']) {
        $update = new StandaloneUpdateExecutor();
        $json['unzipping'] = $update->unzippApp();
    }


    if (isset($_GET['unzipAppExecStep'])) {
        $update = new StandaloneUpdateExecutor();
        $json['unzipping'] = $update->unzipAppExecStep(intval($_GET['unzipAppExecStep']));
    }


    if (isset($_GET['unzippAppGetNumberOfStepsNeeded']) && $_GET['unzippAppGetNumberOfStepsNeeded'] == 1) {
        $update = new StandaloneUpdateExecutor();
        $json['unzipping'] = $update->unzippAppGetNumberOfStepsNeeded();
    }

    if (isset($_GET['replaceFiles']) && $_GET['replaceFiles'] == 1) {
        $update = new StandaloneUpdateExecutor();
        $json['replacing'] = $update->replaceFiles();
    }
    if (isset($_GET['replaceFilesPrepareStepsNeeded']) && $_GET['replaceFilesPrepareStepsNeeded'] == 1) {
        $update = new StandaloneUpdateExecutor();
        $json['replace_steps'] = $update->replaceFilesPrepareStepsNeeded();
    }
    if (isset($_GET['replaceFilesExecStep'])) {
        $update = new StandaloneUpdateExecutor();
        $json['replace_step_result'] = $update->replaceFilesExecStep(intval($_GET['replaceFilesExecStep']));
    }
    if (isset($_GET['replaceFilesExecCleanupStep'])) {
        $update = new StandaloneUpdateExecutor();
        $json['clean_step_result'] = $update->replaceFilesExecCleanupStep();
    }

    if (isset($_GET['isStarted']) && $_GET['isStarted'] == 1) {
        $update = new StandaloneUpdateExecutor();
        $json['started'] = $update->isStarted();
    }

    if (isset($_GET['getLogfile']) && $_GET['getLogfile'] == 1) {
        $update = new StandaloneUpdateExecutor();
        $json['logfile'] = $update->getLogfile();
    }

    print(json_encode($json));
    exit;
}


