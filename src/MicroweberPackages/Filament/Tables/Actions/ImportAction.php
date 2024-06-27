<?php

namespace MicroweberPackages\Filament\Tables\Actions;

use Closure;
use Filament\Tables\Actions\ImportAction as ImportTableAction;
use Illuminate\Support\Arr;
use League\Csv\Reader as CsvReader;
use League\Csv\Statement;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImportAction extends \Filament\Tables\Actions\ImportAction
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->action(function (\Filament\Actions\ImportAction|ImportTableAction $action, array $data) {

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

            $importRecords = [];
            foreach($csvResults->getRecords() as $record) {
                $recordMap = [];
                foreach($data['columnMap'] as $field => $originalField) {
                    $recordMap[$field] = $record[$originalField];
                }

                $importRecords[] = $recordMap;
            }

         //   dd($importRecords);

        });

    }
}
