<?php

namespace Modules\Backup;

class SessionStepper
{
    /**
     * The session id for stepper
     * @var
     */
    public static $sessionId;

    /**
     * @var string
     */
    public static $cacheFilename = 'session_stepper_step';

    /**
     * @var string
     */
    public static $cachePath = 'cache/session_stepper/';

    public static function setSessionId($sessionId)
    {
        self::$sessionId = $sessionId;

        // Create the session file if it doesn't exist
        if (!is_file(self::sessionFilepath())) {
            // Instead of throwing an exception, create the session file
            file_put_contents(self::sessionFilepath(), json_encode([
                'started_at' => date('Y-m-d H:i:s'),
                'session_id' => $sessionId,
                'total_steps' => 5, // Default value
                'step' => 0,
                'data' => [],
            ]));
            
            if (!is_file(self::sessionFilepath())) {
                throw new \Exception('Failed to create session file.');
            }
        }
    }

    public static function cachePath()
    {
        $cacheDir = storage_path() .'/backup/' . self::$cachePath;
        if (!is_dir($cacheDir)) {
            mkdir_recursive($cacheDir);
        }

        return $cacheDir;
    }

    public static function generateSessionId(int $totalSteps = 5, $data = [])
    {
        $sessionId = uniqid(time());
        self::$sessionId = $sessionId;


       file_put_contents(self::sessionFilepath(), json_encode([
            'started_at' => date('Y-m-d H:i:s'),
            'session_id' => $sessionId,
            'total_steps' => $totalSteps,
            'step' => 0,
            'data' => $data,
        ]));
        $saveSessionFile = is_file(self::sessionFilepath());
        if (!$saveSessionFile) {
            throw new \Exception('Can\'t generate session id.');
        }

        return $sessionId;
    }

    public static function nextStep()
    {
        $cacheFile = self::getSessionFileData();
        $step = (int)$cacheFile['step'];

        $cacheFile['step'] = $step + 1;

        if ($cacheFile['step'] == $cacheFile['total_steps']) {
            $cacheFile['done'] = true;
            $cacheFile['finished_at'] = date('Y-m-d H:i:s');
        }

        file_put_contents(self::sessionFilepath(), json_encode($cacheFile));
    }

    public static function finish()
    {

        $cacheFile = self::getSessionFileData();
        $cacheFile['done'] = true;
        $cacheFile['step'] = $cacheFile['total_steps'];
        $cacheFile['finished_at'] = date('Y-m-d H:i:s');

        file_put_contents(self::sessionFilepath(), json_encode($cacheFile));
    }


    public static function totalSteps()
    {
        $cacheFile = self::getSessionFileData();
        $totalSteps = (int)$cacheFile['total_steps'];

        return $totalSteps;
    }

    public static function currentStep()
    {
        $cacheFile = self::getSessionFileData();
        return (int)$cacheFile['step'];
    }

    public static function getSessionFileData()
    {
        if (!is_file(self::sessionFilepath())) {
            throw new \Exception('Session file missing');
        }

        $cacheFile = file_get_contents(self::sessionFilepath());
        $cacheFile = json_decode($cacheFile, true);

        if (!isset($cacheFile['step'])) {
            throw new \Exception('Session file is broken');
        }

        if (!isset($cacheFile['total_steps'])) {
            throw new \Exception('Session file is broken');
        }

        return $cacheFile;
    }

    public static function sessionFilepath()
    {
        if (!self::$sessionId) {
            throw new \Exception('Session id is missing');
        }

        return self::cachePath() . self::$sessionId . '.sess';
    }

    public static function clearSteps()
    {
        return unlink(self::sessionFilepath());
    }

    public static function percentage()
    {
        $currentStep = self::currentStep();
        if ($currentStep < 1) {
            return 100;
        }
        return (int)round((($currentStep * 100) / self::totalSteps()), 2);
    }

    public static function isFinished()
    {
        return (self::currentStep() >= self::totalSteps() ? true : false);
    }

    public static function isFirstStep()
    {
        return (self::currentStep() == 1 ? true : false);
    }

    public static function recomendedSteps($countOfContents)
    {
        $parts = 10;

        if ($countOfContents > 100) {
            $parts = 10;
        }

        if ($countOfContents > 200) {
            $parts = 30;
        }

        if ($countOfContents > 300) {
            $parts = 60;
        }

        if ($countOfContents > 500) {
            $parts = 100;
        }

        if ($countOfContents > 600) {
            $parts = 200;
        }

        if ($countOfContents > 1000) {
            $parts = 500;
        }

        return $parts;
    }
}
