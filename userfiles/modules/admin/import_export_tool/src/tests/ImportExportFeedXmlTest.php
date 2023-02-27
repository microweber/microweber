<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\tests;

use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Import\Formats\XlsxReader;
use MicroweberPackages\Import\Formats\XmlReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\ExportWizard;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\ImportWizard;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Install;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\StartExportingModal;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\StartImportingModal;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;
use MicroweberPackages\Page\Models\Page;

class ImportExportFeedXmlTest extends TestCase
{
    public function testInstall()
    {
        Livewire::test(Install::class)->call('startInstalling');
    }

    public function testImportExportWizard()
    {
        $zip = new \ZipArchive();
        $zip->open(__DIR__ . '/simple-data.zip');
        $content = $zip->getFromName('mw-export-format-products.xml');
        $zip->close();

        if (!is_dir(storage_path().'/import-export-tool/')) {
            mkdir_recursive(storage_path().'/import-export-tool/');
        }

        $page = new Page();
        $page->title = 'Shop';
        $page->is_shop = 1;
        $page->save();

        $fakerFile = UploadedFile::fake()
            ->createWithContent('mw-export-format-products.xml', $content);

        $fullFilePath = ImportFeed::getImportTempPath() . 'uploaded_files' . DS . $fakerFile->name;

        mkdir_recursive(dirname($fullFilePath));
        file_put_contents($fullFilePath, $content);

        $instance = Livewire::test(ImportWizard::class)
            ->call('selectImportTo', 'products')
            ->set('import_feed.source_type', 'upload_file')
            ->call('readUploadedFile', $fakerFile->name)
            ->assertDispatchedBrowserEvent('read-feed-from-file')
            ->assertSuccessful()
            ->assertSee('Feed is uploaded successfully');

        $importFeed = ImportFeed::where('id', $instance->importFeedId)->first()->toArray();

        $shopProductId = Page::where('is_shop',1)->first()->id;

        $instance->set('import_feed.content_tag', 'products.product')
            ->call('changeContentTag')
            ->assertEmitted('dropdownMappingPreviewRefresh')
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
            ->call('selectExportFormat', 'xml');

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
        $dryProductsRead = new XmlReader($importFeed['source_file_realpath']);
        $getDryProducts = $dryProductsRead->readData()['content']['product'];

        // Read exported products
        $exportFeedFilename = backup_location() . $exportModal->export_feed_filename;
        $exportFeedRead = new XmlReader($exportFeedFilename);

        $getExportedProducts = [];
        foreach ($exportFeedRead->readData()['content']['products'] as $product) {
            $getExportedProducts[$product['id']] = $product;
        }

       /* return;

        $this->assertNotEmpty($getExportedProducts);

        foreach ($getDryProducts as $dryProduct) {

            $exportedProduct = $getExportedProducts[$dryProduct['id']];

            $this->assertEquals($dryProduct['id'], $exportedProduct['id']);
            $this->assertEquals($dryProduct['title']['en_us'], $exportedProduct['title']);
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

        }*/

    }
}
