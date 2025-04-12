<?php

namespace Modules\Newsletter\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Newsletter\Filament\Imports\NewsletterSubscriberImporter;
use Modules\Newsletter\Filament\Exports\NewsletterSubscriberExporter;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterSubscriberList;
use Filament\Actions\Imports\Models\Import as FilamentImport;
use Filament\Actions\Exports\Models\Export as FilamentExport;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use ReflectionClass;

class NewsletterSubscriberImportExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setImporterDataAndOptions($importer, $data, $options)
    {
        $ref = new ReflectionClass($importer);
        $dataProp = $ref->getProperty('data');
        $dataProp->setAccessible(true);
        $dataProp->setValue($importer, $data);

        $optionsProp = $ref->getProperty('options');
        $optionsProp->setAccessible(true);
        $optionsProp->setValue($importer, $options);
    }

    #[Test]
    public function it_can_import_subscribers_to_new_list()
    {
        $data = [
            ['name' => 'Alice', 'email' => 'alice@example.com'],
            ['name' => 'Bob', 'email' => 'bob@example.com'],
        ];
        $options = [
            'select_list' => 'import_to_new_list',
            'new_list_name' => 'Test List',
        ];
        $columnMap = [
            'name' => 'name',
            'email' => 'email',
        ];

        $import = new FilamentImport();
        $import->importer = NewsletterSubscriberImporter::class;

        foreach ($data as $row) {
            $importer = $import->getImporter($columnMap, $options);
            $this->setImporterDataAndOptions($importer, $row, $options);
            $importer->resolveRecord();
        }

        $this->assertDatabaseHas('newsletter_subscribers', ['email' => 'alice@example.com']);
        $this->assertDatabaseHas('newsletter_subscribers', ['email' => 'bob@example.com']);
        $this->assertDatabaseHas('newsletter_lists', ['name' => 'Test List']);

        $list = NewsletterList::where('name', 'Test List')->first();
        $this->assertNotNull($list);

        $alice = NewsletterSubscriber::where('email', 'alice@example.com')->first();
        $bob = NewsletterSubscriber::where('email', 'bob@example.com')->first();

        $this->assertDatabaseHas('newsletter_subscribers_lists', [
            'subscriber_id' => $alice->id,
            'list_id' => $list->id,
        ]);
        $this->assertDatabaseHas('newsletter_subscribers_lists', [
            'subscriber_id' => $bob->id,
            'list_id' => $list->id,
        ]);
    }

    #[Test]
    public function it_can_import_subscribers_to_existing_list()
    {
        $list = NewsletterList::factory()->create(['name' => 'Existing List']);
        $data = [
            ['name' => 'Charlie', 'email' => 'charlie@example.com'],
        ];
        $options = [
            'select_list' => 'import_to_existing_list',
            'lists' => [$list->id],
        ];
        $columnMap = [
            'name' => 'name',
            'email' => 'email',
        ];

        $import = new FilamentImport();
        $import->importer = NewsletterSubscriberImporter::class;

        foreach ($data as $row) {
            $importer = $import->getImporter($columnMap, $options);
            $this->setImporterDataAndOptions($importer, $row, $options);
            $importer->resolveRecord();
        }

        $this->assertDatabaseHas('newsletter_subscribers', ['email' => 'charlie@example.com']);
        $this->assertDatabaseHas('newsletter_subscribers_lists', [
            'subscriber_id' => NewsletterSubscriber::where('email', 'charlie@example.com')->first()->id,
            'list_id' => $list->id,
        ]);
    }

    #[Test]
    public function it_can_export_subscribers()
    {
        $list = NewsletterList::factory()->create(['name' => 'Export List']);
        $subscriber = NewsletterSubscriber::factory()->create(['email' => 'export@example.com']);
        NewsletterSubscriberList::factory()->create([
            'subscriber_id' => $subscriber->id,
            'list_id' => $list->id,
        ]);

        $options = [];
        $columnMap = [
            'email' => 'email',
        ];

        $export = new FilamentExport();
        $export->exporter = NewsletterSubscriberExporter::class;

        $exporter = $export->getExporter($columnMap, $options);

        $records = NewsletterSubscriber::all();
        $exported = [];
        foreach ($records as $record) {
            $row = [];
            foreach ($exporter::getColumns() as $column) {
                $row[$column->getName()] = $record->{$column->getName()};
            }
            $exported[] = $row;
        }

        $this->assertTrue(
            collect($exported)->contains(function ($row) {
                return isset($row['email']) && $row['email'] === 'export@example.com';
            })
        );
    }

    #[Test]
    public function it_can_import_and_export_10000_emails_and_cleanup()
    {
        $count = 10000;
        $list = NewsletterList::factory()->create(['name' => 'Bulk List']);

        // Generate 10,000 fake emails
        $data = [];
        for ($i = 1; $i <= $count; $i++) {
            $data[] = [
                'name' => "User $i",
                'email' => "user{$i}@example.com",
            ];
        }
        $options = [
            'select_list' => 'import_to_existing_list',
            'lists' => [$list->id],
        ];
        $columnMap = [
            'name' => 'name',
            'email' => 'email',
        ];

        $import = new FilamentImport();
        $import->importer = NewsletterSubscriberImporter::class;

        // Import in batches for performance
        foreach (array_chunk($data, 500) as $batch) {
            foreach ($batch as $row) {
                $importer = $import->getImporter($columnMap, $options);
                $this->setImporterDataAndOptions($importer, $row, $options);
                $importer->resolveRecord();
            }
        }

        $this->assertEquals($count, NewsletterSubscriber::count());
        $this->assertEquals($count, NewsletterSubscriberList::where('list_id', $list->id)->count());

        // Export and verify
        $export = new FilamentExport();
        $export->exporter = NewsletterSubscriberExporter::class;
        $exporter = $export->getExporter(['email' => 'email'], []);
        $records = NewsletterSubscriber::all();
        $exported = [];
        foreach ($records as $record) {
            $row = [];
            foreach ($exporter::getColumns() as $column) {
                $row[$column->getName()] = $record->{$column->getName()};
            }
            $exported[] = $row;
        }
        $this->assertCount($count, $exported);

        // Cleanup: delete all
        NewsletterSubscriberList::where('list_id', $list->id)->delete();
        NewsletterSubscriber::query()->delete();
        NewsletterList::where('id', $list->id)->delete();

        $this->assertEquals(0, NewsletterSubscriberList::count());
        $this->assertEquals(0, NewsletterSubscriber::count());
        $this->assertEquals(0, NewsletterList::where('id', $list->id)->count());
    }

    #[Test]
    public function it_can_import_emails()
    {
        $list = NewsletterList::factory()->create();
        $subscribers = NewsletterSubscriber::factory()->count(20000)->create();

        // Attach all subscribers to the list in batches
        $subscriberListData = [];
        foreach ($subscribers as $subscriber) {
            $subscriberListData[] = [
                'subscriber_id' => $subscriber->id,
                'list_id' => $list->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        // Batch insert for SQLite compatibility
        foreach (array_chunk($subscriberListData, 500) as $batch) {
            NewsletterSubscriberList::insert($batch);
        }

        $this->assertEquals(20000, NewsletterSubscriber::count());
        $this->assertEquals(20000, NewsletterSubscriberList::where('list_id', $list->id)->count());

        // Step 2: Create a campaign
        $campaign = NewsletterCampaign::factory()->create([
            'list_id' => $list->id,
            'recipients_from' => 'specific_list',
        ]);

        // Step 3: Simulate sending emails (create send log entries) in batches
        $sendLogData = [];
        foreach ($subscribers as $subscriber) {
            $sendLogData[] = [
                'campaign_id' => $campaign->id,
                'subscriber_id' => $subscriber->id,
                'is_sent' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        foreach (array_chunk($sendLogData, 500) as $batch) {
            NewsletterCampaignsSendLog::insert($batch);
        }

        $this->assertEquals(20000, NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)->count());

        NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)->delete();
        NewsletterSubscriberList::where('list_id', $list->id)->delete();
        NewsletterSubscriber::query()->delete();
        NewsletterCampaign::where('id', $campaign->id)->delete();
        NewsletterList::where('id', $list->id)->delete();

        $this->assertEquals(0, NewsletterCampaignsSendLog::count());
        $this->assertEquals(0, NewsletterSubscriberList::count());
        $this->assertEquals(0, NewsletterSubscriber::count());
        $this->assertEquals(0, NewsletterCampaign::count());
        $this->assertEquals(0, NewsletterList::where('id', $list->id)->count());
    }

}
