<?php

namespace MicroweberPackages\Core\tests;

use PHPUnit\Framework\Attributes\Test;

class SessionsTest extends TestCase
{
    #[Test]
    public function it_sessions(): void {
        $expected = 'Session var '.rand();
        session_set('my_sess_var', $expected);
        $session_var = session_get('my_sess_var');
        $this->assertEquals($session_var, $expected);
    }
}
