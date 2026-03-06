<?php
namespace Modules\Restore\Tests;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Modules\Restore\Formats\ZipReader;
use Modules\Restore\Loggers\RestoreLogger;


/**
 * Run test
 * @author Bobi Microweber
 * @command php phpunit.phar --filter Restore
 */

class ReadersTest extends TestCase
{
	#[Test]
	public function it_zip_reader(): void {

        RestoreLogger::clearLog();
        $zip = new ZipReader(__DIR__ . DS. 'strange-file.zip');
        $zip->extractZipData();
        $zip->clearCache();
        $log = RestoreLogger::getLog();

        $this->assertTrue((strpos($log, 'The zip file has no files.')!==false));


        RestoreLogger::clearLog();
        Config::set('microweber.allow_php_files_upload', true);

        $zip = new ZipReader(__DIR__ . DS. 'strange-file.zip');
        $zip->extractZipData();
        $zip->clearCache();

        $log = RestoreLogger::getLog();

        $this->assertTrue((strpos($log, 'queue strange-file.php')!==false));
        $this->assertTrue((strpos($log, 'no files to import')!==false));

    }
}
