<?php

namespace MicroweberPackages\Modules\Admin\ImportExportTool\tests;


use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\DropdownMapping;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\DropdownMappingPreview;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\ExportWizard;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\FeedReport;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\ImportWizard;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\Install;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\NewImportModal;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\NoExportFeeds;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\StartExportingModal;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\StartImportingModal;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Admin\ViewImport;
use MicroweberPackages\User\tests\UserLivewireComponentsAccessTest;
/**
 * @runTestsInSeparateProcesses
 */
class ImportExportToolLivewireComponentsAccessTest extends UserLivewireComponentsAccessTest
{
    public $componentsList = [
        DropdownMapping::class,
        DropdownMappingPreview::class,
        ExportWizard::class,
        FeedReport::class,
        ImportWizard::class,
        Install::class,
        NewImportModal::class,
        NoExportFeeds::class,
        StartExportingModal::class,
        StartImportingModal::class,
        ViewImport::class,
    ];
}

