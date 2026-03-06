<?php
namespace Modules\Content\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use MicroweberPackages\Utils\Misc\ContentExport;

class ContentExportTest extends TestCase
{

	#[Test]

	public function it_export(): void {
		$export = new ContentExport();
		$export->setExportFormatType('json');
		$exportStatus = $export->start();

		$this->assertArrayHasKey('filename', $exportStatus);
		$this->assertArrayHasKey('success', $exportStatus);
	}

	#[Test]

	public function it_export_with_wrong_format(): void {
		$export = new ContentExport();
		$export->setExportFormatType('xmla');
		$exportStatus = $export->start();

		$this->assertArrayHasKey('error', $exportStatus);
	}

	#[Test]

	public function it_export_with_wrong_file_download(): void {
		$export = new ContentExport();

		$download = $export->download('wfafwa');

		$this->assertArrayHasKey('error', $download);
	}
}
