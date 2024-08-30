@extends('import_export_tool::admin.module-layout')

@section('module-content')
    <div class="mt-4 px-0">

        <a href="{{route('admin.import-export-tool.export-wizard')}}" class="btn btn-outline-dark">
            {{'New export'}}
        </a>

        @if($export_feeds->count() == 0)
            <livewire:import_export_tool::no_export_feeds />
        @else
            <div class="mb-3 mt-3">
                <label class="form-label">{{'All exports'}}</label>
            </div>

           <div class="table-responsive">
               <table class="table card-table table-vcenter">
                   <thead>
                   <tr>
                       <th scope="col">#</th>
                       <th scope="col">{{'Name'}}</th>
                       <th scope="col">{{'Type'}}</th>
                       <th scope="col">{{'Format'}}</th>
                       <th scope="col">{{'Created at'}}</th>
                       <th scope="col">{{'Updated at'}}</th>
                       <th scope="col">{{'Action'}}</th>
                   </tr>
                   </thead>
                   <tbody>
                   @foreach($export_feeds as $feed)
                       <tr class="cursor-pointer" onclick="window.location.href='{{route('admin.import-export-tool.import-wizard')}}?importFeedId={{$feed->id}}'">
                           <th scope="row">{{$feed->id}}</th>
                           <td>
                               <a href="{{route('admin.import-export-tool.import-wizard')}}?importFeedId={{$feed->id}}">{{$feed->name}}</a>
                           </td>
                           <td>{{ucfirst($feed->export_type)}}</td>
                           <td>{{ucfirst($feed->export_format)}}</td>
                           <td>{{$feed->created_at}}</td>
                           <td>{{$feed->updated_at}}</td>
                           <td>
                               <a class="btn btn-outline-primary btn-sm" href="{{route('admin.import-export-tool.export-wizard')}}?exportFeedId={{$feed->id}}">
                                   {{'View'}}
                               </a>

                               <a href="{{ $feed->download_link }}" target="_new" class="btn btn-outline-success btn-sm">
                                   {{'Download'}}
                               </a>

                               <a href="{{route('admin.import-export-tool.delete-wizard-file', $feed->id)}}" class="btn btn-outline-danger btn-sm">
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
