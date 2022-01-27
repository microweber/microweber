<?php

namespace MicroweberPackages\Backup;

use MicroweberPackages\Backup\Loggers\BackupLogger;
use MicroweberPackages\Export\Export;
use MicroweberPackages\Export\SessionStepper;

class GenerateBackup extends Export
{
    public $type = 'json';
    public $exportAllData = true;

    public function __construct() {
        $this->logger = new BackupLogger();
    }

    public function start()
    {
        if (!$this->sessionId) {
            return array("error" => "SessionId is missing.");
        }

        SessionStepper::setSessionId($this->sessionId);

        $log = array();
        $log['current_step'] = SessionStepper::currentStep();
        $log['total_steps'] =  SessionStepper::totalSteps();
        $log['precentage'] = SessionStepper::percentage();
        $log['data'] = false;
        $log['session_id'] = $this->sessionId;

        if (SessionStepper::isFinished()) {
            $log['done'] = true;
        } else {
            // sleep(rand(1,7));
            SessionStepper::nextStep();
        }

        return $log;

    }
}
