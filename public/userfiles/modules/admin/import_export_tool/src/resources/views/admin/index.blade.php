@extends('import_export_tool::admin.module-layout')

@section('module-content')
<div class="mt-4 px-0">

    <a href="{{route('admin.import-export-tool.import-wizard')}}" class="btn btn-outline-dark">
        {{'New import'}}
    </a>

    @if($import_feeds->count() == 0)
        <livewire:import_export_tool::no_feeds />
    @else
    <div class="mb-3 mt-3">
        <label class="form-label">
            {{'All imports'}}
        </label>
    </div>
    <div class="table-responsive">
        <table class="table card-table table-vcenter">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">{{'Name'}}</th>
                <th scope="col">{{'Type'}}</th>
                <th scope="col">{{'Items Count'}}</th>
                <th scope="col">{{'Process time'}}</th>
                <th scope="col">{{'Filesize'}}</th>
                <th scope="col">{{'Created at'}}</th>
                <th scope="col">{{'Updated at'}}</th>
                <th scope="col">{{'Action'}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($import_feeds as $feed)
                <tr class="cursor-pointer" onclick="window.location.href='{{route('admin.import-export-tool.import-wizard')}}?importFeedId={{$feed->id}}'">
                    <th scope="row">{{$feed->id}}</th>
                    <td>
                        <a href="{{route('admin.import-export-tool.import-wizard')}}?importFeedId={{$feed->id}}">{{$feed->name}}</a>
                    </td>
                    <td>{{ucfirst($feed->import_to)}}</td>
                    <td>{{$feed->count_of_contents}}</td>
                    <td>
                        @php
                            $importStart = Carbon::createFromDate($feed->last_import_start);
                            $importEnd = Carbon::createFromDate($feed->last_import_end);
                            echo $importStart->diffInMinutes($importEnd);
                        @endphp {{'min'}}
                    </td>
                    <td>{{mw()->format->human_filesize($feed->source_file_size)}}</td>
                    <td>{{$feed->created_at}}</td>
                    <td>{{$feed->updated_at}}</td>
                    <td>
                        <a class="btn btn-outline-primary btn-sm" href="{{route('admin.import-export-tool.import-wizard')}}?importFeedId={{$feed->id}}">{{'View'}}</a>


                        <a href="{{route('admin.import-export-tool.import-delete', $feed->id)}}" class="btn btn-outline-danger btn-sm">
                            {{'Delete'}}
                        </a>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @endif
</div>
@endsection
