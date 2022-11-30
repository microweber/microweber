<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\tests;

use Livewire\Livewire;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Import\Formats\XlsxReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\ExportWizard;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Install;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\StartExportingModal;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;
use MicroweberPackages\Product\Models\Product;

class ExportFeedTest extends TestCase
{
    public function testInstall()
    {
        Livewire::test(Install::class)->call('startInstalling');
    }

    public function testExportWizard()
    {
        $zip = new \ZipArchive();
        $zip->open(__DIR__ . '/simple-data.zip');
        $content = $zip->getFromName('data-example-1.xml');
        $zip->close();

        $tempName = tempnam(storage_path(),'xml');
        file_put_contents($tempName, $content);


        dd($content);


        $instance = Livewire::test(ExportWizard::class)
            ->call('selectExportType', 'products')
            ->call('selectExportFormat', 'xlsx');

        $exportFeedId = $instance->export_feed['id'];
        $findExportFeed = ExportFeed::where('id', $exportFeedId)->first();
        $this->assertNotNull($findExportFeed);

        $exportModal = Livewire::test(StartExportingModal::class, [$exportFeedId]);

        $totalSteps = $exportModal->export_log['total_steps'];

        for ($i=1; $i<=$totalSteps; $i++) {
            $exportModal->call('nextStep');
        }

        $this->assertEquals($exportModal->export_log['current_step'],$totalSteps);
        $this->assertEquals($exportModal->export_log['percentage'],100);
        $this->assertTrue($exportModal->done);
        $this->assertNotEmpty($exportModal->download_file);

        $exportFeedFilename = backup_location() . $exportModal->export_feed_filename;
        $read = new XlsxReader($exportFeedFilename);
        $getProducts = $read->readData()['content'];
        $this->assertNotEmpty($getProducts);


    }
}
