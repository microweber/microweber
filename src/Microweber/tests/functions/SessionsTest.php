<?php

namespace FunctionsTest;


class SessionsTest extends \PHPUnit_Framework_TestCase
{
    function __construct()
    {
        // session_provider for unit tests,
        // does not persist values between requests
        mw('user')->session_provider = 'array';
    }

    public function testSessions()
    {


        $expected = "Session var " . rand();
        session_set('my_sess_var', $expected);

        $session_var = session_get('my_sess_var');

        //PHPUnit
        $this->assertEquals($session_var, $expected);
    }


}