<?php

namespace Modules\Newsletter\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Newsletter\Filament\Exports\NewsletterSubscriberExporter;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Modules\Newsletter\Traits\Exportable;
use League\Csv\Writer;
use League\Csv\CannotInsertRecord;
use SplTempFileObject;
use ZipArchive;

class NewsletterSubscriberExportController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    use Exportable;

    public function export(Request $request)
    {
        $selectedColumns = $request->input('columns', []);
        $selectedIds = $request->input('selected_ids', ''); // For bulk export
        $exportMultiple = filter_var($request->input('export_multiple', false), FILTER_VALIDATE_BOOLEAN);

        // Prepare the query
        $query = NewsletterSubscriber::query();
        if (!empty($selectedIds)) {
            $ids = explode(',', $selectedIds);
            $query->whereIn('id', $ids);
        }

        $subscribers = $query->get();

        if ($exportMultiple) {
            return $this->exportToMultipleFiles($subscribers, $selectedColumns);
        } else {
            return $this->exportToSingleFile($subscribers, $selectedColumns);
        }
    }

    private function exportToSingleFile($subscribers, $selectedColumns)
    {
        $headers = [];
        $exportData = [];

        // Prepare headers and data based on selected columns
        if (!empty($selectedColumns)) {
            foreach ($selectedColumns as $column) {
                $headers[] = ucfirst($column);
            }

            foreach ($subscribers as $subscriber) {
                $rowData = [];
                foreach ($selectedColumns as $column) {
                    $rowData[] = data_get($subscriber, $column);
                }
                $exportData[] = $rowData;
            }
        } else {
            // If no columns are selected, export all columns
            $headers = array_keys((new NewsletterSubscriber())->toArray());
            foreach ($subscribers as $subscriber) {
                $exportData[] = array_values($subscriber->toArray());
            }
        }

        $filename = 'newsletter_subscribers_' . date('YmdHis') . '.csv';
        return $this->_streamCsv($exportData, $headers, $filename);
    }

    private function exportToMultipleFiles($subscribers, $selectedColumns)
    {
        $zipFilename = 'newsletter_subscribers_' . date('YmdHis') . '.zip';
        $zipPath = backup_location() . $zipFilename;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            throw new \Exception("Cannot open zip archive");
        }

        $chunkSize = 1000;
        $chunks = $subscribers->chunk($chunkSize);
        $fileCount = 1;
        $toRemove = [];
        foreach ($chunks as $chunk) {
            $csvFilename = 'newsletter_subscribers_part' . $fileCount . '_' . date('YmdHis') . '.csv';
            $csvPath = backup_location() . $csvFilename;

            $headers = [];
            $exportData = [];

            // Prepare headers and data based on selected columns
            if (!empty($selectedColumns)) {
                foreach ($selectedColumns as $column) {
                    $headers[] = ucfirst($column);
                }

                foreach ($chunk as $subscriber) {
                    $rowData = [];
                    foreach ($selectedColumns as $column) {
                        $rowData[] = data_get($subscriber, $column);
                    }
                    $exportData[] = $rowData;
                }
            } else {
                // If no columns are selected, export all columns
                $headers = array_keys((new NewsletterSubscriber())->toArray());
                foreach ($chunk as $subscriber) {
                    $exportData[] = array_values($subscriber->toArray());
                }
            }

            $csvContent = $this->getCsvContent($exportData, $headers);

            file_put_contents($csvPath, $csvContent);
            $zip->addFile($csvPath, $csvFilename);
            $fileCount++;
            $toRemove[] = $csvPath;

        }

        $zip->close();


        foreach ($toRemove as $csvPath) {
            if (file_exists($csvPath)) {
                unlink($csvPath);
            }
        }

        return response()->download($zipPath, $zipFilename, [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    private function getCsvContent(array $data, array $headers = []): string
    {
        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->setOutputBOM(Writer::BOM_UTF8);

        // Add headers
        if (!empty($headers)) {
            try {
                $csv->insertOne($headers);
            } catch (CannotInsertRecord $e) {
                \Log::error('Cannot insert headers to CSV: ' . $e->getMessage());
            }
        }

        // Add data
        foreach ($data as $row) {
            try {
                $csv->insertOne($row);
            } catch (CannotInsertRecord $e) {
                \Log::error('Cannot insert row to CSV: ' . $e->getMessage());
            }
        }

        return $csv->getContent();
    }
}
