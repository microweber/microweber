@extends('import_export_tool::admin.import-wizard.layout')

@section('content')

    @if($tab == 'type')
        @include('import_export_tool::admin.import-wizard.import-type')
    @endif

    @if($tab == 'upload')
        @include('import_export_tool::admin.import-wizard.upload')
    @endif

@endsection
