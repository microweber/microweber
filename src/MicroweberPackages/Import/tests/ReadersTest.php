<?php
namespace MicroweberPackages\Import\tests;

use Illuminate\Support\Facades\Config;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Import\Formats\ZipReader;
use MicroweberPackages\Import\Loggers\ImportLogger;


/**
 * Run test
 * @author Bobi Microweber
 * @command php phpunit.phar --filter Import
 */

class ReadersTest extends TestCase
{
	public function testZipReader() {

        ImportLogger::clearLog();
        $zip = new ZipReader(__DIR__ . DS. 'strange-file.zip');
        $zip->readData();
        $zip->clearCache();
        $log = ImportLogger::getLog();

        $this->assertTrue((strpos($log, 'The zip file has no files.')!==false));


        ImportLogger::clearLog();
        Config::set('microweber.allow_php_files_upload', true);

        $zip = new ZipReader(__DIR__ . DS. 'strange-file.zip');
        $zip->readData();
        $zip->clearCache();

        $log = ImportLogger::getLog();

        $this->assertTrue((strpos($log, 'queue strange-file.php')!==false));
        $this->assertTrue((strpos($log, 'no files to import')!==false));

    }
}
