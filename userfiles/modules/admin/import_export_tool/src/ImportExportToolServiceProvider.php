<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Modules\Admin\ImportExportTool;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\Counter;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\DropdownMapping;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\ExportWizard;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\FeedReport;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\FieldMapDropdownItem;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\DropdownMappingPreview;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\ImportWizard;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\NewImportModal;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\NoExportFeeds;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\NoFeeds;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\StartImportingModal;
use MicroweberPackages\Modules\Admin\ImportExportTool\Http\Livewire\ViewImport;

class ImportExportToolServiceProvider extends ServiceProvider
{
    /**
     * Whether or not to defer the loading of this service
     * provider until it's needed
     *
     * @var boolean
     */
//    protected $defer = true;


    public function provides()
    {
        return ['MicroweberPackages\Modules\Admin\ImportExportTool'];
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */


    public function boot()
    {

        $this->loadViewsFrom( __DIR__. '/resources/views/components', 'import_export_tool');

        Livewire::component('import_export_tool::import_wizard', ImportWizard::class);
        Livewire::component('import_export_tool::export_wizard', ExportWizard::class);
        Livewire::component('import_export_tool::no_feeds', NoFeeds::class);
        Livewire::component('import_export_tool::no_export_feeds', NoExportFeeds::class);
        Livewire::component('import_export_tool::feed_report', FeedReport::class);
        Livewire::component('import_export_tool::new_import_modal', NewImportModal::class);
        Livewire::component('import_export_tool::start_importing_modal', StartImportingModal::class);
        Livewire::component('import_export_tool::view_import', ViewImport::class);
        Livewire::component('import_export_tool::dropdown_mapping_preview', DropdownMappingPreview::class);
        Livewire::component('import-export-tool::dropdown_mapping', DropdownMapping::class);
    }

    public function register()
    {
      //  $this->loadMigrationsFrom(normalize_path((__DIR__) . '/migrations/'));
        $this->loadRoutesFrom((__DIR__) . '/routes/admin.php');
        $this->loadMigrationsFrom(__DIR__ . '/migrations/');

        View::addNamespace('import_export_tool', normalize_path((__DIR__) . '/resources/views'));
    }
}
