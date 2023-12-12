<?php
namespace MicroweberPackages\Security\tests;

use MicroweberPackages\Core\tests\TestCase;

class HtmlCleanTest extends TestCase
{
    public function testHtmlClean()
    {


        $clean = new \MicroweberPackages\Helper\HTMLClean();

        $options = [];

        $xss = "<script>alert('xss')</script>";
        $xss .= "<div onmousedown='11'><script>alert('xss')</script></div>";
        $xss .= "<module type='test' onmousedown='11'><script>alert('xss')</script></module>";

        $check = $clean->clean($xss, $options);

        $expected = "<div></div>";
        $this->assertEquals($expected, $check);

        // test admin mode
        $options = [];
        $options['admin_mode'] = true;
        $check = $clean->clean($xss, $options);
        $expected = '<div></div><module type="test"></module>';
        $this->assertEquals($expected, $check);

    }
}
