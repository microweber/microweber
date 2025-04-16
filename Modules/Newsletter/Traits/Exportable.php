<?php

namespace Modules\Newsletter\Traits;

use Symfony\Component\HttpFoundation\StreamedResponse;
use League\Csv\Writer;
use League\Csv\CannotInsertRecord;
use SplTempFileObject;

trait Exportable
{
    protected string $type = 'csv';
    public $exportFileName;

    protected function _generateFilename($name = false)
    {
        if ($name) {
            $exportFilename = $name . '.' . $this->type;
        } else {
            $exportFilename = 'export_' . date("Y-m-d-his") . '.' . $this->type;
        }

        if (isset($this->exportFileName) && !empty($this->exportFileName)) {
            $exportFilename = $this->exportFileName . '.' . $this->type;
        }

        $exportFilename = normalize_path($exportFilename, false);

        return array(
            'download' => route('admin.backup.download') . '?file=' . $exportFilename,
            'filepath' => backup_location() . $exportFilename,
            'filename' => $exportFilename
        );
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

            }
        }

        // Add data
        foreach ($data as $row) {
            try {
                $csv->insertOne($row);
            } catch (CannotInsertRecord $e) {

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
