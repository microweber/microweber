@extends('import_export_tool::admin.import-wizard.layout')

@section('content')

    <div>
        <label>Upload Type</label>
        <select class="form-select mb-3">
            <option value="1" selected>Download feed from link</option>
            <option value="2">Upload feed from your computer</option>
        </select>
    </div>

@endsection
