<?php

namespace Goodby\CSV\Import\Tests\Standard\Join;

class CSVFiles
{
    public static function getShiftJisCsv()
    {
        return __DIR__.'/csv_files/sjis.csv';
    }

    public static function getMacExcelCsv()
    {
        return __DIR__.'/csv_files/mac-excel.csv';
    }

    public static function getMacExcelLines()
    {
        return array(
            array('a', 'b', 'c'),
            array('d', 'e', 'f'),
        );
    }

    public static function getTabSeparatedCsv()
    {
        return __DIR__.'/csv_files/tab-separated.csv';
    }

    public static function getTabSeparatedLines()
    {
        return array(
            array('value1', 'value2', 'value3'),
            array('value4', 'value5', 'value6'),
        );
    }

    public static function getColonSeparatedCsv()
    {
        return __DIR__.'/csv_files/colon-separated.csv';
    }

    public static function getColonSeparatedLines()
    {
        return array(
            array('value1', 'value2', 'value3'),
            array('value4', 'value5', 'value6'),
        );
    }

    public static function getUtf8Csv()
    {
        return __DIR__.'/csv_files/utf-8.csv';
    }

    public static function getUtf8Lines()
    {
        return array(
            array('✔', '✔', '✔', '✔'),
            array('★', '★', '★', '★'),
            array('유', '니', '코', '드'),
        );
    }

    public static function getIssue5CSV()
    {
        return __DIR__.'/csv_files/issue-5.csv';
    }
}
