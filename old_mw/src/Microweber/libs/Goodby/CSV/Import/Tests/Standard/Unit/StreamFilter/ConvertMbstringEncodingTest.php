<?php

namespace Goodby\CSV\Import\Tests\Standard\Unit\StreamFilter;

use Expose\Expose as e;
use Goodby\CSV\Import\Standard\StreamFilter\ConvertMbstringEncoding;

class ConvertMbstringEncodingTest extends \PHPUnit_Framework_TestCase
{
    private $internalEncodingBackup;

    public function setUp()
    {
        $this->internalEncodingBackup = mb_internal_encoding();
    }

    public function tearDown()
    {
        mb_internal_encoding($this->internalEncodingBackup);
    }

    public function testGetFilterName()
    {
        $this->assertSame('convert.mbstring.encoding.*', ConvertMbstringEncoding::getFilterName());
    }

    public function testOneParameter()
    {
        $filterString = 'convert.mbstring.encoding.EUC-JP';
        mb_internal_encoding('UTF-7');
        $filter = new ConvertMbstringEncoding();
        $filter->filtername = $filterString;
        $filter->onCreate();
        $this->assertAttributeSame('EUC-JP', 'fromCharset', $filter);
        $this->assertAttributeSame('UTF-7', 'toCharset', $filter);
    }

    public function testTwoParameters()
    {
        $filterString = 'convert.mbstring.encoding.SJIS-win:UTF-8';
        mb_internal_encoding('UTF-7');
        $filter = new ConvertMbstringEncoding();
        $filter->filtername = $filterString;
        $filter->onCreate();
        $this->assertAttributeSame('SJIS-win', 'fromCharset', $filter);
        $this->assertAttributeSame('UTF-8', 'toCharset', $filter);
    }

    public function test_when_invalid_parameter_given_it_returns_false()
    {
        $filterString = 'convert.mbstring.encoding.@#$#!%^^';
        $filter = new ConvertMbstringEncoding();
        $filter->filtername = $filterString;
        $this->assertFalse($filter->onCreate());
    }

    public function test_register_filter()
    {
        ConvertMbstringEncoding::register();
        $filterName = ConvertMbstringEncoding::getFilterName();
        $registeredFilters = stream_get_filters();
        $this->assertTrue(in_array($filterName, $registeredFilters));
    }
}
