<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithConsoleEvents;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use WithConsoleEvents;

}
