<?php

namespace Modules\Order\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use Modules\Order\Filament\Exports\OrderExporter;
use Modules\Order\Models\Order;
use Symfony\Component\HttpFoundation\StreamedResponse;
use League\Csv\Writer;
use League\Csv\CannotInsertRecord;
use SplTempFileObject;
use ZipArchive;

class OrderExportController extends AdminController
{
    public function export(Request $request): StreamedResponse
    {
        $selectedColumns = $request->input('columns', []);
        $selectedIds = $request->input('selected_ids', '');
        $exportMultiple = filter_var($request->input('export_multiple', false), FILTER_VALIDATE_BOOLEAN);

        // Prepare the query
        $query = Order::query();
        if (!empty($selectedIds)) {
            $ids = explode(',', $selectedIds);
            $query->whereIn('id', $ids);
        }

        $orders = $query->get();

        if ($exportMultiple) {
            return $this->exportToMultipleFiles($orders, $selectedColumns);
        } else {
            return $this->exportToSingleFile($orders, $selectedColumns);
        }
    }

    private function exportToSingleFile($orders, $selectedColumns): StreamedResponse
    {
        $headers = [];
        $exportData = [];

        // Prepare headers and data based on selected columns
        if (!empty($selectedColumns)) {
            foreach ($selectedColumns as $column) {
                $headers[] = ucfirst(str_replace('_', ' ', $column));
            }

            foreach ($orders as $order) {
                $rowData = [];
                foreach ($selectedColumns as $column) {
                    $rowData[] = data_get($order, $column);
                }
                $exportData[] = $rowData;
            }
        } else {
            // If no columns are selected, export all columns
            $headers = array_keys((new Order())->toArray());
            foreach ($orders as $order) {
                $exportData[] = array_values($order->toArray());
            }
        }

        $filename = 'orders_' . date('YmdHis') . '.csv';
        return $this->_streamCsv($exportData, $headers, $filename);
    }

    private function exportToMultipleFiles($orders, $selectedColumns): StreamedResponse
    {
        $zipFilename = 'orders_' . date('YmdHis') . '.zip';
        $zipPath = backup_location() . $zipFilename;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            throw new \Exception("Cannot open zip archive");
        }

        $chunkSize = 1000;
        $chunks = $orders->chunk($chunkSize);
        $fileCount = 1;

        foreach ($chunks as $chunk) {
            $csvFilename = 'orders_part' . $fileCount . '_' . date('YmdHis') . '.csv';
            $csvPath = backup_location() . $csvFilename;

            $headers = [];
            $exportData = [];

            // Prepare headers and data based on selected columns
            if (!empty($selectedColumns)) {
                foreach ($selectedColumns as $column) {
                    $headers[] = ucfirst(str_replace('_', ' ', $column));
                }

                foreach ($chunk as $order) {
                    $rowData = [];
                    foreach ($selectedColumns as $column) {
                        $rowData[] = data_get($order, $column);
                    }
                    $exportData[] = $rowData;
                }
            } else {
                // If no columns are selected, export all columns
                $headers = array_keys((new Order())->toArray());
                foreach ($chunk as $order) {
                    $exportData[] = array_values($order->toArray());
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
