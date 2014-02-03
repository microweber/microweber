<?php

namespace FunctionsTest;


class Sessions extends \PHPUnit_Framework_TestCase
{

    public function testSessions()
    {
        // session_provider for unit tests,
        // does not persist values between requests
        mw('user')->session_provider = 'array';

        $expected = "Session var " . rand();
        session_set('my_sess_var', $expected);

        $session_var = session_get('my_sess_var');

        //PHPUnit
        $this->assertEquals($session_var, $expected);
    }


}