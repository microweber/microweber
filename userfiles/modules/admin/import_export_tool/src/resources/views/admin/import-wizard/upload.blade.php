@extends('import_export_tool::admin.import-wizard.layout')

@section('content')

    <div class="d-flex justify-content-center">

        <div style="width:550px;">
            <div class="mb-2">Upload File Type</div>
            <select class="form-control mb-3 w-100">
                <option value="download">Download feed from link</option>
                <option value="upload">Upload feed from your computer</option>
            </select>
        </div>

        

    </div>

@endsection
