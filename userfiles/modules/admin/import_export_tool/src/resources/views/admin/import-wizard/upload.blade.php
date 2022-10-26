@extends('import_export_tool::admin.import-wizard.layout')

@section('content')

    <div class="d-flex justify-content-center">

        <div style="width:550px;">
            <div class="mb-2">Upload File Type</div>
            <select class="form-control mb-3 w-100" wire:model="import_feed.source_type">
                <option value="download_link">Download feed from link</option>
                <option value="upload_file">Upload feed from your computer</option>
            </select>
        </div>



    </div>

@endsection
