<?php

class StandaloneUpdateExecutor
{

    public $branch = 'master';


    public function getLogfile()
    {
        return $this->getInstallSessionId() . 'log.txt';
    }

    public function log($log)
    {
        @file_put_contents($this->getLogfile(), $log . PHP_EOL);
    }

    public function getInstallSessionId()
    {
        return $_COOKIE['install_session_id'];
    }

    public function startSession()
    {
        if (!isset($_COOKIE['install_session_id'])) {
            $this->setCookie('install_session_id', rand(2222, 4444));
            $this->log('Starting the installation session..');
            return true;
        }

        return false;
    }

    public function setCookie($key, $value)
    {
        $_COOKIE[$key] = $value;
        return setcookie($key, $value, time() + (1800), "/");
    }

    public function setInstallVersion($version = 'latest')
    {
        setcookie('install_session_version', $version, time() + (1800), "/");
    }

    public function isStarted()
    {
        if (!isset($_COOKIE['install_session_id'])) {
            return false;
        }
        return true;
    }

    public function latestVersion()
    {

        $latestMasterVersionZip = 'http://updater.microweberapi.com/microweber-master.zip';

        return ['url' => $latestMasterVersionZip];
    }

    public function latestDevVersion()
    {

        $latestDevVersionZip = 'http://updater.microweberapi.com/microweber-dev.zip';

        return ['url' => $latestDevVersionZip];
    }

   public function latestDevUnstableVersion()
   {
        $latestDevVersionZip = 'http://updater.microweberapi.com/microweber-dev-unstable.zip';

        return ['url' => $latestDevVersionZip];
    }

    public function startUpdating()
    {
        $version = 'latest';

        if (isset($_COOKIE['install_session_version']) && $_COOKIE['install_session_version'] == 'dev') {
            $version = 'developer';
            $installVersion = $this->latestDevVersion();
        } else if (isset($_COOKIE['install_session_version']) && $_COOKIE['install_session_version'] == 'dev_unstable') {
           $version = 'developer unstable';
           $installVersion = $this->latestDevUnstableVersion();
       } else {
            $installVersion = $this->latestVersion();
            if (!$installVersion['url']) {
                $this->log("We can't download latest version right now.");
                return ['status' => 'failed', 'downloaded' => false];
            }
        }

        $this->log('Downloading ' . $version . ' version of system.');

        $zipFile = time() . 'mw-app.zip';

        $downloadStatus = $this->downloadFile($installVersion['url'], $zipFile);
        if ($downloadStatus) {
            $this->log('The ' . $version . ' version of the system has been downloaded successfully!');
        }

        $this->setCookie('install_version_source', $version);
        $this->setCookie('install_version_zip_file', $zipFile);

        return ['status' => 'success', 'downloaded' => true];
    }

    public function unzippAppGetNumberOfStepsNeeded()
    {
        $version = $_COOKIE['install_version_source'];
        $zipFile = $_COOKIE['install_version_zip_file'];

        $version = $this->sanitizeString($version);
        $zipFile = $this->sanitizeString($zipFile);


        $this->log('Unzipping ' . $version . ' version files...');
        $filesInZip = [];

        $zip = new \ZipArchive;
        $res = $zip->open(__DIR__ . DIRECTORY_SEPARATOR . $zipFile);
        if ($res === TRUE) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename_only = $zip->getNameIndex($i);
                $filesInZip[] = $filename_only;
            }
        }

        if (is_object($res)) {
            try {
                $zip->close();
            } catch (Exception $e) {
                //   $this->log('Error: ' . $e->getMessage());
            }

        }


        if ($filesInZip) {
            $chunks = array_chunk($filesInZip, 200);

            $steps_file = __DIR__ . DIRECTORY_SEPARATOR . 'unzip_steps.json';

            $json = json_encode($chunks);
            file_put_contents($steps_file, $json);

            return ['status' => 'success', 'unzip_steps_needed' => count($chunks)];

        }


    }

    public function sanitizeString($string)
    {
        $string = strtolower(trim(preg_replace('/[^A-Za-z0-9-_.]+/', '-', $string)));
        $string = str_replace('..', '', $string);
        $string = str_replace('\\', '', $string);
        $string = str_replace('/', '', $string);

        return $string;
    }

    public function unzipAppExecStep($step)
    {
        $step = intval($step);
        $version = $_COOKIE['install_version_source'];
        $zipFile = $_COOKIE['install_version_zip_file'];
        $version = $this->sanitizeString($version);
        $zipFile = $this->sanitizeString($zipFile);
        $extractToFolder = __DIR__ . DIRECTORY_SEPARATOR . 'mw-app-unziped';
        $this->log('Extracting files from archive step ' . $step . ' of ' . $version . ' version files...');

        $zip = new \ZipArchive;
        $res = $zip->open(__DIR__ . DIRECTORY_SEPARATOR . $zipFile);
        if ($res === TRUE) {
            $steps_file = __DIR__ . DIRECTORY_SEPARATOR . 'unzip_steps.json';
            $steps = json_decode(file_get_contents($steps_file), true);
            if (isset($steps[$step])) {
                $files = $steps[$step];
                $logOnce = false;


                foreach ($files as $file) {
                    $file = normalize_path($file, false);
                    if (!$logOnce) {
                        //   $this->log('Unzipping ' . basename($file). ' ...');
                        $logOnce = true;
                    }
                    $dn = dirname($extractToFolder . DIRECTORY_SEPARATOR . $file);
                    if (!is_dir($dn)) {
                        mkdir_recursive($dn);
                    }

                    // $extractThefile = $zip->extractTo($extractToFolder, $file);
                }
                $extractThefile = $zip->extractTo($extractToFolder, $files);


            }
        }
        if (is_object($zip)) {
            $zip->close();
        }
        return ['status' => 'success', 'unzip_step_executed' => $step];

    }

    public function unzippApp()
    {
        $version = $_COOKIE['install_version_source'];
        $zipFile = $_COOKIE['install_version_zip_file'];
        $version = $this->sanitizeString($version);
        $zipFile = $this->sanitizeString($zipFile);

        $this->log('Unzipping ' . $version . ' version files...');

        $zip = new \ZipArchive;
        $res = $zip->open(__DIR__ . DIRECTORY_SEPARATOR . $zipFile);
        if ($res === TRUE) {
            $zip->extractTo(__DIR__ . DIRECTORY_SEPARATOR . 'mw-app-unziped');
            $zip->close();
            $this->log('Unzipping ' . $version . ' version files done!');
            return ['status' => 'success', 'unzipped' => true];
        } else {
            $this->log('Error unzipping the ' . $version . ' version of the system!');
            return ['status' => 'failed', 'unzipped' => false];
        }
    }

    public function replaceFilesPrepareStepsNeeded()
    {
        $this->log('Preparing replace steps...');

        $replace = new StandaloneUpdateReplacer();
        $steps = $replace->prepareSteps();

        return ['status' => 'success', 'steps_needed' => $steps];

    }


    public function replaceFilesExecStep($step)
    {

        $replace = new StandaloneUpdateReplacer();
        $replace->logger = $this;
        $step = $replace->replaceFilesExecStep($step);

        return ['status' => 'success', 'step_executed' => $step];

    }

    public function replaceFilesExecCleanupStep()
    {

        $replace = new StandaloneUpdateReplacer();
        $replace->logger = $this;
        $step = $replace->replaceFilesExecCleanupStep();


        return ['status' => 'success', 'step_executed' => $step];

    }

    public function replaceFiles()
    {
        $this->log('Replacing with the new files...');

        $replace = new StandaloneUpdateReplacer();
        $replace->start();

        $message = 'You are up to date!';

        /*
        if (!empty($_COOKIE['site_url'])) {
            $siteUrl = $_COOKIE['site_url'];
            $message .= '<br /><a href="'.$siteUrl.'">Visit your website</a>';
        }*/

        if (!empty($_COOKIE['admin_url'])) {
            $adminUrl = $_COOKIE['admin_url'];
            $message .= '<br /><a href="' . $adminUrl . '" class="btn btn-link" style="color:#fff">Back to admin</a>';
        }

        $this->log(json_encode(['success' => true, 'message' => $message]));

        return ['status' => 'success', 'replaced' => true];
    }

    public function downloadFile($url, $dest)
    {
        set_time_limit(0);
        $logFile = $this->getLogfile();
        $options = array(
            CURLOPT_FILE => is_resource($dest) ? $dest : fopen($dest, 'w'),
            CURLOPT_TIMEOUT => 600,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_VERBOSE => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_URL => $url,
            CURLOPT_FAILONERROR => true, // HTTP code > 400 will throw curl error
        );


        if (isset($_COOKIE['max_receive_speed_download']) && $_COOKIE['max_receive_speed_download'] > 0) {
            $speedLimit = (intval($_COOKIE['max_receive_speed_download']) * 1024 * 1024);
            $options[CURLOPT_MAX_RECV_SPEED_LARGE] = $speedLimit;
        }

        if ($logFile) {
            $options[CURLOPT_STDERR] = fopen($logFile, 'a+');
            $options[CURLOPT_WRITEHEADER] = fopen($logFile, 'a+');
        }


        $options[CURLOPT_PROGRESSFUNCTION] = array($this, 'downloadFileProgress');
        $options[CURLOPT_BUFFERSIZE] = 1000000;
        $options[CURLOPT_NOPROGRESS] = false;


        $ch = curl_init();

        curl_setopt_array($ch, $options);

        $return = curl_exec($ch);

        if ($return === false) {
            return curl_error($ch);
        } else {
            curl_close($ch);
            return true;
        }
    }

    public function downloadFileProgress($resource, $downloadSize, $downloaded, $uploadSize, $uploaded)
    {
        if ($downloadSize > 0 and $downloaded > 0) {
            $percent = round(($downloaded / $downloadSize) * 100, 2);
            $this->log('Downloaded ' . $percent . '%');
        }
        // $this->log('Downloaded ' . $downloaded . ' of ' . $downloadSize . ' bytes.');


//         [
//            'resource' => $resource,
//            'download_size' => $downloadSize,
//            'downloaded' => $downloaded,
//            'upload_size' => $uploadSize,
//            'uploaded' => $uploaded
//        ]

    }
}
