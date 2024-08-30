<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\tests;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Import\Formats\XlsxReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\ExportWizard;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\ImportWizard;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\Install;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\StartExportingModal;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\StartImportingModal;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class ImportExportFeedXlsxTest extends TestCase
{

    public function testInstall()
    {
        //delete the old migrations and drop the tables
        $migrations = [
            '2022_00_00_000001_create_import_feeds_table',
            '2022_00_00_000003_create_export_feeds_table',
        ];
        $drop = [
            'import_feeds',
            'export_feeds',
        ];
        $delete = \DB::table('migrations')->whereIn('migration', $migrations)->delete();

        foreach ($drop as $table) {
            if (\Schema::hasTable($table)) {
                \Schema::drop($table);
            }
        }

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

       Livewire::test(Install::class)->call('startInstalling');
    }

    public function testImportExportWizard()
    {

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);


        $zip = new \ZipArchive();
        $zip->open(__DIR__ . '/simple-data.zip');
        $content = $zip->getFromName('mw-export-format-products.xlsx');
        $zip->close();

        if (!is_dir(storage_path().'/import-export-tool/')) {
            mkdir_recursive(storage_path().'/import-export-tool/');
        }

        $page = new Page();
        $page->title = 'Shop';
        $page->is_shop = 1;
        $page->save();

        $fakerFile = UploadedFile::fake()
            ->createWithContent('mw-export-format-products.xlsx', $content);

        $fullFilePath = ImportFeed::getImportTempPath() . 'uploaded_files' . DS . $fakerFile->name;

        mkdir_recursive(dirname($fullFilePath));
        file_put_contents($fullFilePath, $content);

        $instance = Livewire::test(ImportWizard::class)
                ->call('selectImportTo', 'products')
                ->set('import_feed.source_type', 'upload_file')
                ->call('readUploadedFile', $fakerFile->name)
                ->assertDispatched('read-feed-from-file')
                ->assertSuccessful()
                ->assertSee('Feed is uploaded successfully');

        $importFeed = ImportFeed::where('id', $instance->importFeedId)->first()->toArray();

        $shopProductId = Page::where('is_shop',1)->first()->id;

        $instance->set('import_feed.content_tag', 'Worksheet')
                ->call('changeContentTag')
                ->assertDispatched('dropdownMappingPreviewRefresh')
                ->assertSee('Feed is read successfully')
                ->call('saveMapping')
                ->set('import_feed.primary_key', 'id')
                ->set('import_feed.parent_page', $shopProductId);

        // Let's import with modal
        $importModal = Livewire::test(StartImportingModal::class, [$instance->importFeedId]);

        $totalSteps = $importModal->import_log['total_steps'];

        for ($i=1; $i<=$totalSteps; $i++) {
            $importModal->call('nextStep');
        }
        $this->assertEquals($importModal->import_log['current_step'],$totalSteps);
        $this->assertEquals($importModal->import_log['percentage'],100);

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


        // Read dry products
        $dryProductsRead = new XlsxReader($importFeed['source_file_realpath']);
        $getDryProducts = $dryProductsRead->readData()['content'];

        // Read exported products
        $exportFeedFilename = backup_location() . $exportModal->export_feed_filename;
        $exportFeedRead = new XlsxReader($exportFeedFilename);
        $getExportedProducts = [];
        foreach ($exportFeedRead->readData()['content'] as $product) {
            $getExportedProducts[$product['id']] = $product;
        }

        $this->assertNotEmpty($getExportedProducts);

        foreach ($getDryProducts as $dryProduct) {

            $exportedProduct = $getExportedProducts[$dryProduct['id']];

            $this->assertEquals($dryProduct['id'], $exportedProduct['id']);
            $this->assertEquals($dryProduct['title'], $exportedProduct['title']);
            $this->assertEquals($dryProduct['content_body'], $exportedProduct['content_body']);
            $this->assertEquals($dryProduct['content_meta_title'], $exportedProduct['content_meta_title']);
            $this->assertEquals($dryProduct['content_meta_keywords'], $exportedProduct['content_meta_keywords']);
            $this->assertEquals($dryProduct['price'], $exportedProduct['price']);
            $this->assertEquals($dryProduct['special_price'], $exportedProduct['special_price']);
            $this->assertEquals($dryProduct['qty'], $exportedProduct['qty']);
            $this->assertEquals($dryProduct['sku'], $exportedProduct['sku']);
            $this->assertEquals($dryProduct['barcode'], $exportedProduct['barcode']);
            $this->assertEquals($dryProduct['weight'], $exportedProduct['weight']);
            $this->assertEquals($dryProduct['weight_type'], $exportedProduct['weight_type']);
            $this->assertEquals($dryProduct['width'], $exportedProduct['width']);
            $this->assertEquals($dryProduct['height'], $exportedProduct['height']);
            $this->assertEquals($dryProduct['depth'], $exportedProduct['depth']);

        }

    }
}
