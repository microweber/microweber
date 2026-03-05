<?php

namespace Modules\Invoice\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Invoice\Filament\Exports\InvoiceExporter;
use Modules\Invoice\Models\Invoice;
use Symfony\Component\HttpFoundation\StreamedResponse;
use League\Csv\Writer;
use League\Csv\CannotInsertRecord;
use SplTempFileObject;
use ZipArchive;

class InvoiceExportController extends \MicroweberPackages\Admin\Http\Controllers\AdminController
{
    public function export(Request $request): StreamedResponse
    {
        $selectedColumns = $request->input('columns', []);
        $selectedIds = $request->input('selected_ids', ''); // For bulk export
        $exportMultiple = filter_var($request->input('export_multiple', false), FILTER_VALIDATE_BOOLEAN);

        // Prepare the query
        $query = Invoice::query();
        if (!empty($selectedIds)) {
            $ids = explode(',', $selectedIds);
            $query->whereIn('id', $ids);
        }

        $invoices = $query->get();

        if ($exportMultiple) {
            return $this->exportToMultipleFiles($invoices, $selectedColumns);
        } else {
            return $this->exportToSingleFile($invoices, $selectedColumns);
        }
    }

    private function exportToSingleFile($invoices, $selectedColumns): StreamedResponse
    {
        $headers = [];
        $exportData = [];

        // Prepare headers and data based on selected columns
        if (!empty($selectedColumns)) {
            foreach ($selectedColumns as $column) {
                $headers[] = ucfirst(str_replace('_', ' ', $column));
            }

            foreach ($invoices as $invoice) {
                $rowData = [];
                foreach ($selectedColumns as $column) {
                    $rowData[] = data_get($invoice, $column);
                }
                $exportData[] = $rowData;
            }
        } else {
            // If no columns are selected, export all columns
            $headers = array_keys((new Invoice())->toArray());
            foreach ($invoices as $invoice) {
                $exportData[] = array_values($invoice->toArray());
            }
        }

        $filename = 'invoices_' . date('YmdHis') . '.csv';
        return $this->_streamCsv($exportData, $headers, $filename);
    }

    private function exportToMultipleFiles($invoices, $selectedColumns): StreamedResponse
    {
        $zipFilename = 'invoices_' . date('YmdHis') . '.zip';
        $zipPath = backup_location() . $zipFilename;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            throw new \Exception("Cannot open zip archive");
        }

        $chunkSize = 1000;
        $chunks = $invoices->chunk($chunkSize);
        $fileCount = 1;

        foreach ($chunks as $chunk) {
            $csvFilename = 'invoices_part' . $fileCount . '_' . date('YmdHis') . '.csv';
            $csvPath = backup_location() . $csvFilename;

            $headers = [];
            $exportData = [];

            // Prepare headers and data based on selected columns
            if (!empty($selectedColumns)) {
                foreach ($selectedColumns as $column) {
                    $headers[] = ucfirst(str_replace('_', ' ', $column));
                }

                foreach ($chunk as $invoice) {
                    $rowData = [];
                    foreach ($selectedColumns as $column) {
                        $rowData[] = data_get($invoice, $column);
                    }
                    $exportData[] = $rowData;
                }
            } else {
                // If no columns are selected, export all columns
                $headers = array_keys((new Invoice())->toArray());
                foreach ($chunk as $invoice) {
                    $exportData[] = array_values($invoice->toArray());
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

    protected function _streamCsv(array $data, array $headers = [], $filename = 'export.csv'): StreamedResponse
    {
        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->setOutputBOM(Writer::BOM_UTF8);

        // Add headers
        if (!empty($headers)) {
            try {
                $csv->insertOne($headers);
            } catch (CannotInsertRecord $e) {
                // Log error if needed
            }
        }

        // Add data
        foreach ($data as $row) {
            try {
                $csv->insertOne($row);
            } catch (CannotInsertRecord $e) {
                // Log error if needed
            }
        }

        return new StreamedResponse(
            function () use ($csv) {
                echo $csv->toString();
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }
}
