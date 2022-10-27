@extends('import_export_tool::admin.import-wizard.layout')

@section('content')

    <div class="row">
        <div class="mx-auto col-md-8 text-center">

            <button class="btn btn-primary btn-rounded"
                    wire:click="$emit('openModal', 'import_export_tool_start_importing_modal',{importFeedId:{{$importFeedId}}})">
                <i class="fa fa-file-import"></i> Start Importing
            </button>

        </div>
    </div>
@endsection
