<?php

namespace MicroweberPackages\Export;

class SessionStepper
{
    /**
     * The current batch stepper.
     * @var integer
     */
    public static $currentStep = 0;

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
        self::$sessionId = $sessionId;
    }

    public static function generateSessionId() {

       $sessionId = uniqid(time());

       $cacheDir = userfiles_path() . self::$cachePath;
       if (!is_dir($cacheDir)) {
           mkdir_recursive($cacheDir);
       }

       file_put_contents($cacheDir . $sessionId.'.sess', $sessionId);

       return $sessionId;
    }

    public static function nextStep()
    {
        cache_save(self::currentStep() + 1, self::$cacheFilename, self::$sessionId, 60 * 10);
    }

    public static function totalSteps()
    {
        return self::$totalSteps;
    }

    public static function currentStep()
    {
        self::$currentStep = (int)cache_get(self::$cacheFilename, self::$sessionId);

        return self::$currentStep;
    }

    public function clearSteps()
    {
        cache_delete(self::$sessionId);
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
