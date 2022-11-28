<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\tests;

use Livewire\Livewire;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\ExportWizard;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Install;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\StartExportingModal;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;

class ExportFeedTest extends TestCase
{
    public function testInstall()
    {
        Livewire::test(Install::class)->call('startInstalling');

    }

    public function testExportWizard()
    {
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
    }
}
