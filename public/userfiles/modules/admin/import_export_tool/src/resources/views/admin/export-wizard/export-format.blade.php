<div class="d-flex justify-content-center">

    <button type="button" wire:click="selectExportFormat('csv')" class="import-wizard-select-type">
        <div class="mt-2">
            <img style="width:80px" src="{{module_url('admin\import_export_tool')}}images/supported-file-formats/csv.svg" />
        </div>
    </button>

    <button type="button" wire:click="selectExportFormat('xlsx')" class="import-wizard-select-type">
        <div class="mt-2">
            <img style="width:80px" src="{{module_url('admin\import_export_tool')}}images/supported-file-formats/excel.svg" />
        </div>
    </button>

    <button type="button" wire:click="selectExportFormat('xml')" class="import-wizard-select-type">
        <div class="mt-2">
            <img style="width:80px" src="{{module_url('admin\import_export_tool')}}images/supported-file-formats/xml.svg" />
        </div>
    </button>


</div>
