@extends('import_export_tool::admin.import-wizard.layout')

@section('content')

    <div class="d-flex justify-content-center">
        <div style="width:550px;">
            <div class="mb-2">Upload Type</div>
            <select class="selectpicker mb-3 w-100">
                <option value="1" selected data-icon="mdi mdi-link">Download feed from link</option>
                <option value="2" data-icon="mdi mdi-upload">Upload feed from your computer</option>
            </select>
        </div>


    </div>

@endsection
