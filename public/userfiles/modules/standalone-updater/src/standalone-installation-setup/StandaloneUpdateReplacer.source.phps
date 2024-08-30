<?php
class StandaloneUpdateReplacer
{
    public $microweberPath;
    public $newMicroweberPath;
    public $logger = null;

    public function __construct()
    {
        $this->microweberPath = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR;
        $this->newMicroweberPath = __DIR__ . DIRECTORY_SEPARATOR . 'mw-app-unziped';
    }

    public function log($mgs)
    {
        if ($this->logger) {
            $this->logger->log($mgs);
        }
    }

    public function replaceFilesExecCleanupStep()
    {
        $steps_file = $this->newMicroweberPath . DIRECTORY_SEPARATOR . 'replace_steps.json';
        return $this->deleteDirectoryRecursive($this->newMicroweberPath);
    }


    public function replaceFilesExecStep($step)
    {
        $step = intval($step);
        if ($step == 0) {
            $this->deleteOldDirectories();
        }

        $steps_file = $this->newMicroweberPath . DIRECTORY_SEPARATOR . 'replace_steps.json';
        $step_data = json_decode(file_get_contents($steps_file), true);

        $total = count(array_keys($step_data));

        if (isset($step_data[$step]) and is_array($step_data[$step]) and !empty($step_data[$step])) {
            $newFilesForCopy = $step_data[$step];
            $this->performFilesCopy($newFilesForCopy);

        }

        $this->log('Completed replace step ' . $step);

        if (intval($step) > 1 and ($total == $step)) {
            $this->log('Update is completed');
        }

        return $step;
    }

    public function prepareSteps()
    {
        if (!is_dir($this->newMicroweberPath)) {
            mkdir_recursive($this->newMicroweberPath);
        }
        $steps_file = $this->newMicroweberPath . DIRECTORY_SEPARATOR . 'replace_steps.json';

        $files = $this->getFilesToCopy();
        // some servers get too many files open error
        $chunks = array_chunk($files, 1024);

        $json = json_encode($chunks);
        file_put_contents($steps_file, $json);

        return count($chunks);
    }

    public function getFilesToCopy()
    {
        $newFilesForCopy = [];
        //add new config files with doNotReplace option
        $newFilesForCopy = array_merge($newFilesForCopy, $this->getFilesFromPath($this->newMicroweberPath . DIRECTORY_SEPARATOR . 'config', ['doNotReplace' => true]));

        //add other files
        //$newFilesForCopy = array_merge($newFilesForCopy, $this->getFilesFromPath($this->newMicroweberPath . DIRECTORY_SEPARATOR . 'userfiles' . DIRECTORY_SEPARATOR . 'templates'));
        $newFilesForCopy = array_merge($newFilesForCopy, $this->getFilesFromPath($this->newMicroweberPath . DIRECTORY_SEPARATOR . 'userfiles' . DIRECTORY_SEPARATOR . 'modules'));
        $newFilesForCopy = array_merge($newFilesForCopy, $this->getFilesFromPath($this->newMicroweberPath . DIRECTORY_SEPARATOR . 'userfiles' . DIRECTORY_SEPARATOR . 'elements'));
        $newFilesForCopy = array_merge($newFilesForCopy, $this->getFilesFromPath($this->newMicroweberPath . DIRECTORY_SEPARATOR . 'src'));
        $newFilesForCopy = array_merge($newFilesForCopy, $this->getFilesFromPath($this->newMicroweberPath . DIRECTORY_SEPARATOR . 'vendor'));
        $newFilesForCopy = array_merge($newFilesForCopy, $this->getFilesFromPath($this->newMicroweberPath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'build'));
        $newFilesForCopy = array_merge($newFilesForCopy, $this->getFilesFromPath($this->newMicroweberPath . DIRECTORY_SEPARATOR . 'resources'));


        $newFilesForCopy[] = ['realPath' => $this->newMicroweberPath . DIRECTORY_SEPARATOR . 'composer.lock', 'targetPath' => 'composer.lock'];
        $newFilesForCopy[] = ['realPath' => $this->newMicroweberPath . DIRECTORY_SEPARATOR . 'composer.json', 'targetPath' => 'composer.json'];

        $newFilesForCopy[] = ['realPath' => $this->newMicroweberPath . DIRECTORY_SEPARATOR . 'version.txt', 'targetPath' => 'version.txt'];
        $newFilesForCopy[] = ['realPath' => $this->newMicroweberPath . DIRECTORY_SEPARATOR . 'ABOUT.md', 'targetPath' => 'ABOUT.md'];
        $newFilesForCopy[] = ['realPath' => $this->newMicroweberPath . DIRECTORY_SEPARATOR . 'README.md', 'targetPath' => 'README.md'];
        $newFilesForCopy[] = ['realPath' => $this->newMicroweberPath . DIRECTORY_SEPARATOR . 'CHANGELOG.md', 'targetPath' => 'CHANGELOG.md'];


        return $newFilesForCopy;
    }

    public function deleteOldDirectories()
    {
        $this->deleteDirectoryRecursive($this->microweberPath . 'vendor');
        $this->deleteDirectoryRecursive($this->microweberPath . 'bootstrap ' . DIRECTORY_SEPARATOR . 'cache');
        $this->deleteDirectoryRecursive($this->microweberPath . 'storage' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'cache');
        $this->deleteDirectoryRecursive($this->microweberPath . 'storage' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'views');
        $this->deleteDirectoryRecursive($this->microweberPath . 'userfiles' . DIRECTORY_SEPARATOR . 'cache');
        $this->deleteDirectoryRecursive($this->microweberPath . 'userfiles' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'admin-css');

    }

    public function performFilesCopy($newFilesForCopy)
    {
        $countCopy = count($newFilesForCopy);
        $copyIterator = 0;
        foreach ($newFilesForCopy as $newFile) {
            $copyIterator++;
            $newFileFolder = dirname($this->microweberPath . $newFile['targetPath']);
            if (!is_dir($newFileFolder)) {
                mkdir_recursive($newFileFolder);
            }
            $target = $this->microweberPath . $newFile['targetPath'];

            if (isset($newFile['doNotReplace']) and $newFile['doNotReplace'] == true and is_file($target)) {
                continue;
            }

            if (is_file($target)) {
                if ($this->filesAreEqual($newFile['realPath'], $target)) {
                    continue;
                }
            }

            if (is_file($target)) {
                @unlink($target);
            }

            @copy($newFile['realPath'], $target);

        }
    }

    public function start()
    {
        $this->deleteOldDirectories();
        $newFilesForCopy = $this->getFilesToCopy();

        $countCopy = count($newFilesForCopy);

        $this->performFilesCopy($newFilesForCopy);

        $this->log(json_encode(['success' => true, 'message' => $countCopy . ' files copied']));

    }


    public function filesAreEqual($a, $b)
    {

        $a = hash_file('CRC32', $a, FALSE);
        $b = hash_file('CRC32', $b, FALSE);

//        $a = file_get_contents($a);
//        $b = file_get_contents($b);
//
//        $a = preg_replace('/\s+/', '', $a);
//        $b = preg_replace('/\s+/', '', $b);
//
//        $a = trim($a);
//        $b = trim($b);
//
        if ($a == $b) {
            return true;
        }

        return false;
    }

    public function setBranch($branch)
    {
        $this->branch = $branch;
    }
    public function deleteDirectoryRecursive($path)
    {
        if (!is_dir($path)) {
            return;
        }

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            @$todo($fileinfo->getRealPath());
        }

        return @rmdir($path);
    }

    public function getFilesFromPath($path, $options = [])
    {
        $filesMap = [];
        if (!is_dir($path)) {
            return $filesMap;
        }
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $fileinfo) {
            if (!$fileinfo->isDir()) {

                $targetPath = $fileinfo->getRealPath();
                $targetPath = str_replace($this->newMicroweberPath, '', $targetPath);
                $options['realPath'] = $fileinfo->getRealPath();
                $options['targetPath'] = $targetPath;
                $filesMap[] = $options;
            }
        }
        return $filesMap;
    }
}
