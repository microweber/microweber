<?php

namespace MicroweberPackages\Export;

class SessionStepper
{

    /**
     * The total steps for stepper.
     * @var integer
     */
    public static $totalSteps = 20;

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

        if (!is_file(self::cachePath() . $sessionId.'.sess')) {
            throw new \Exception('SessionId is not valid.');
        }

        self::$sessionId = $sessionId;
    }

    public static function cachePath()
    {
        $cacheDir = userfiles_path() . self::$cachePath;
        if (!is_dir($cacheDir)) {
            mkdir_recursive($cacheDir);
        }

        return $cacheDir;
    }

    public static function generateSessionId() {

       $sessionId = uniqid(time());

       file_put_contents(self::cachePath() . $sessionId.'.sess', json_encode([
           'started_at'=>date('Y-m-d H:i:s'),
           'session_id'=>$sessionId,
           'total_steps'=>self::$totalSteps,
           'step'=>1
       ]));

       self::$sessionId = $sessionId;

       return $sessionId;
    }

    public static function nextStep()
    {
        $cacheFile = self::getSessionFileData();
        $step = (int) $cacheFile['step'];

        $cacheFile['step'] = $step + 1;

        file_put_contents(self::cachePath() . self::$sessionId.'.sess', json_encode($cacheFile));
    }

    public static function totalSteps()
    {
        $cacheFile = self::getSessionFileData();
        $totalSteps = (int) $cacheFile['total_steps'];

        return $totalSteps;
    }

    public static function currentStep()
    {
        $cacheFile = self::getSessionFileData();
        return (int) $cacheFile['step'];
    }

    public static function getSessionFileData()
    {
        $cacheFile = file_get_contents(self::cachePath() . self::$sessionId.'.sess');
        $cacheFile = json_decode($cacheFile, true);

        if (!isset($cacheFile['step'])) {
            throw new \Exception('Session file is broken.');
        }

        if (!isset($cacheFile['total_steps'])) {
            throw new \Exception('Session file is broken.');
        }

        return $cacheFile;
    }

    public static function clearSteps()
    {
        return unlink(self::cachePath() . self::$sessionId.'.sess');
    }

    public function percentage()
    {
        return number_format(((self::currentStep() * 100) / self::totalSteps()), 2);
    }

    public static function isFinished()
    {
        return (self::currentStep() >= self::totalSteps() ? true : false);
    }

    public static function isFirstStep()
    {
        return (self::currentStep() == 1 ? true : false);
    }
}
