<?php

namespace Modules\Newsletter\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Newsletter\Filament\Exports\NewsletterCampaignExporter;
use Modules\Newsletter\Models\NewsletterCampaign;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Modules\Newsletter\Traits\Exportable;
use League\Csv\Writer;
use League\Csv\CannotInsertRecord;
use SplTempFileObject;
use ZipArchive;

class NewsletterCampaignExportController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    use Exportable;

    public function export(Request $request): StreamedResponse
    {
        $selectedColumns = $request->input('columns', []);
        $selectedIds = $request->input('selected_ids', ''); // For bulk export
        $exportMultiple = filter_var($request->input('export_multiple', false), FILTER_VALIDATE_BOOLEAN);

        // Prepare the query
        $query = NewsletterCampaign::query();
        if (!empty($selectedIds)) {
            $ids = explode(',', $selectedIds);
            $query->whereIn('id', $ids);
        }

        $campaigns = $query->get();

        if ($exportMultiple) {
            return $this->exportToMultipleFiles($campaigns, $selectedColumns);
        } else {
            return $this->exportToSingleFile($campaigns, $selectedColumns);
        }
    }

    private function exportToSingleFile($campaigns, $selectedColumns): StreamedResponse
    {
        $headers = [];
        $exportData = [];

        // Prepare headers and data based on selected columns
        if (!empty($selectedColumns)) {
            foreach ($selectedColumns as $column) {
                $headers[] = ucfirst($column);
            }

            foreach ($campaigns as $campaign) {
                $rowData = [];
                foreach ($selectedColumns as $column) {
                    $rowData[] = data_get($campaign, $column);
                }
                $exportData[] = $rowData;
            }
        } else {
            // If no columns are selected, export all columns
            $headers = array_keys((new NewsletterCampaign())->toArray());
            foreach ($campaigns as $campaign) {
                $exportData[] = array_values($campaign->toArray());
            }
        }

        $filename = 'newsletter_campaigns_' . date('YmdHis') . '.csv';
        return $this->_streamCsv($exportData, $headers, $filename);
    }

     private function exportToMultipleFiles($campaigns, $selectedColumns): StreamedResponse
     {
         $zipFilename = 'newsletter_campaigns_' . date('YmdHis') . '.zip';
         $zipPath = backup_location() . $zipFilename;

         $zip = new ZipArchive();
         if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
             throw new \Exception("Cannot open zip archive");
         }

         $chunkSize = 1000;
         $chunks = $campaigns->chunk($chunkSize);
         $fileCount = 1;

         foreach ($chunks as $chunk) {
             $csvFilename = 'newsletter_campaigns_part' . $fileCount . '_' . date('YmdHis') . '.csv';
             $csvPath = backup_location() . $csvFilename;

             $headers = [];
             $exportData = [];

             // Prepare headers and data based on selected columns
             if (!empty($selectedColumns)) {
                 foreach ($selectedColumns as $column) {
                     $headers[] = ucfirst($column);
                 }

                 foreach ($chunk as $campaign) {
                     $rowData = [];
                     foreach ($selectedColumns as $column) {
                         $rowData[] = data_get($campaign, $column);
                     }
                     $exportData[] = $rowData;
                 }
             } else {
                 // If no columns are selected, export all columns
                 $headers = array_keys((new NewsletterCampaign())->toArray());
                 foreach ($chunk as $campaign) {
                     $exportData[] = array_values($campaign->toArray());
                 }
             }

             $csvContent = $this->getCsvContent($exportData, $headers);

             file_put_contents($csvPath, $csvContent);
             $zip->addFile($csvPath, $csvFilename);
             $fileCount++;
             @unlink($csvPath);
         }

         $zip->close();

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
