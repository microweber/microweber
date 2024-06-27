<?php

namespace MicroweberPackages\Filament;

use Filament\Actions\Imports\Models\Import;
use Filament\Tables\Actions\ImportAction as ImportTableAction;
use Illuminate\Support\Arr;
use League\Csv\Reader as CsvReader;
use League\Csv\Statement;

class MwFilamentImport
{
    public static function startImport(\Filament\Actions\ImportAction|ImportTableAction $action, array $data)
    {
        $csvFile = $data['file'];

        $csvStream = $action->getUploadedFileStream($csvFile);

        if (!$csvStream) {
            return;
        }

        $csvReader = CsvReader::createFromStream($csvStream);

        if (filled($csvDelimiter = $action->getCsvDelimiter($csvReader))) {
            $csvReader->setDelimiter($csvDelimiter);
        }

        $csvReader->setHeaderOffset($action->getHeaderOffset() ?? 0);
        $csvResults = Statement::create()->process($csvReader);

        $user = auth()->user();
        $import = app(Import::class);
        $import->user()->associate($user);
        $import->file_name = $csvFile->getClientOriginalName();
        $import->file_path = $csvFile->getRealPath();
        $import->importer = $action->getImporter();

        $options = array_merge(
            $action->getOptions(),
            Arr::except($data, ['file', 'columnMap']),
        );
        $importerInstance = $import->getImporter($data['columnMap'], $options);


        $importedRecords = [];
        $importedRecordsCount = 0;
        foreach($csvResults->getRecords() as $record) {
            $importerInstance($record);
            if ($importerInstance->getRecord()) {
                $importedRecordsCount++;
                $importedRecords[] = $importerInstance->getRecord();
            }
        }

        return [
            'importOptions' => $options,
            'importedRecords' => $importedRecords,
            'importedRecordsCount' => $importedRecordsCount,
        ];

    }
}
