<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\tests;

use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Import\Formats\XlsxReader;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\ExportWizard;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\ImportWizard;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Install;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\StartExportingModal;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\StartImportingModal;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ExportFeed;
use MicroweberPackages\Modules\Admin\ImportExportTool\Models\ImportFeed;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;

class ImportExportFeedTest extends TestCase
{
    public function testInstall()
    {
        Livewire::test(Install::class)->call('startInstalling');
    }

    public function testImportExportWizard()
    {
        Content::truncate();
        clearcache();

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

        $instance = Livewire::test(ImportWizard::class)
                ->call('selectImportTo', 'products')
                ->set('import_feed.source_type', 'upload_file')
                ->set('upload_file', $fakerFile)
                ->call('upload')
                ->assertDispatchedBrowserEvent('read-feed-from-file')
                ->assertSuccessful()
                ->assertSee('Feed is uploaded successfully');

        $importFeed = ImportFeed::where('id', $instance->importFeedId)->first()->toArray();
        mkdir_recursive(dirname($importFeed['source_file_realpath']));
        file_put_contents($importFeed['source_file_realpath'], $content);

        $shopProductId = Page::where('is_shop',1)->first()->id;

        $instance->set('import_feed.content_tag', 'Worksheet')
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

           // $this->assertSame($exportedProduct['parent_id'], $shopProductId);
            $this->assertSame($dryProduct['id'], $exportedProduct['id']);
            $this->assertSame($dryProduct['title'], $exportedProduct['title']);
            //$this->assertSame($dryProduct['url'], $exportedProduct['url']);
            $this->assertSame($dryProduct['content_body'], $exportedProduct['content_body']);
            $this->assertSame($dryProduct['content_meta_title'], $exportedProduct['content_meta_title']);
            $this->assertSame($dryProduct['content_meta_keywords'], $exportedProduct['content_meta_keywords']);
            $this->assertSame($dryProduct['price'], $exportedProduct['price']);
            $this->assertSame($dryProduct['special_price'], $exportedProduct['special_price']);
            $this->assertSame($dryProduct['qty'], $exportedProduct['qty']);
            $this->assertSame($dryProduct['sku'], $exportedProduct['sku']);
            $this->assertSame($dryProduct['barcode'], $exportedProduct['barcode']);
            $this->assertSame($dryProduct['weight'], $exportedProduct['weight']);
            $this->assertSame($dryProduct['weight_type'], $exportedProduct['weight_type']);
            //$this->assertSame($dryProduct['free_shipping'], $exportedProduct['free_shipping']);
            $this->assertSame($dryProduct['width'], $exportedProduct['width']);
            $this->assertSame($dryProduct['height'], $exportedProduct['height']);
            $this->assertSame($dryProduct['depth'], $exportedProduct['depth']);
          //  $this->assertSame($dryProduct['tags'], $exportedProduct['tags']);

            // dump($dryProduct);dd($exportedProduct);
        }

    }
}
