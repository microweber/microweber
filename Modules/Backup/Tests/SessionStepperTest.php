<?php

namespace MicroweberPackages\Export\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Export\SessionStepper;

class SessionStepperTest extends TestCase
{
    public function testSteps()
    {

        // First generate session id
        $sessionId = SessionStepper::generateSessionId(3);
        $this->assertNotEmpty($sessionId);
        $this->assertEquals(SessionStepper::totalSteps(), 3);

        $currentStep = SessionStepper::currentStep();
        $this->assertEquals($currentStep, 0);
        $this->assertFalse(SessionStepper::isFinished());

        // Up with one step
        SessionStepper::nextStep();
        $currentStep = SessionStepper::currentStep();
        $this->assertEquals($currentStep, 1);
        $this->assertTrue(SessionStepper::isFirstStep());
        $this->assertFalse(SessionStepper::isFinished());

        SessionStepper::nextStep();
        $currentStep = SessionStepper::currentStep();
        $this->assertEquals($currentStep, 2);
        $this->assertFalse(SessionStepper::isFinished());

        SessionStepper::nextStep();
        $currentStep = SessionStepper::currentStep();
        $this->assertEquals($currentStep, 3);
        $this->assertTrue(SessionStepper::isFinished());

        $fileData = SessionStepper::getSessionFileData();

        $this->assertEquals($sessionId, $fileData['session_id']);
        $this->assertEquals('3', $fileData['total_steps']);
        $this->assertEquals('3', $fileData['step']);
        $this->assertTrue($fileData['done']);

    }
}
