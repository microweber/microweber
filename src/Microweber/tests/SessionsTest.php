<?php

namespace Microweber\tests;

class SessionsTest extends TestCase
{
    public function testSessions()
    {
        $expected = 'Session var '.rand();
        session_set('my_sess_var', $expected);
        $session_var = session_get('my_sess_var');
        $this->assertEquals($session_var, $expected);
    }
}
