<?php
namespace MicroweberPackages\Restore\tests;

use Illuminate\Support\Facades\Config;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Restore\Formats\ZipReader;
use MicroweberPackages\Restore\Loggers\RestoreLogger;


/**
 * Run test
 * @author Bobi Microweber
 * @command php phpunit.phar --filter Restore
 */

class ReadersTest extends TestCase
{
	public function testZipReader() {

        RestoreLogger::clearLog();
        $zip = new ZipReader(__DIR__ . DS. 'strange-file.zip');
        $zip->readData();
        $zip->clearCache();
        $log = RestoreLogger::getLog();

        $this->assertTrue((strpos($log, 'The zip file has no files.')!==false));


        RestoreLogger::clearLog();
        Config::set('microweber.allow_php_files_upload', true);

        $zip = new ZipReader(__DIR__ . DS. 'strange-file.zip');
        $zip->readData();
        $zip->clearCache();

        $log = RestoreLogger::getLog();

        $this->assertTrue((strpos($log, 'queue strange-file.php')!==false));
        $this->assertTrue((strpos($log, 'no files to import')!==false));

    }
}
