<module type="admin/modules/info"/>

<div class="card style-1 mb-3">

    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="admin/import_export_tool" />
    </div>

    <div class="card-body pt-3">
        <div class="row">
        <div class="col-md-12">


            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Imports</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Exports</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">


                    @if($import_feeds->count() == 0)

                        <livewire:import_export_tool_no_feeds />

                    @else
                        <table class="table table-primary-hover">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Type</th>
                                <th scope="col">Items Count</th>
                                <th scope="col">Process time</th>
                                <th scope="col">Filesize</th>
                                <th scope="col">Created at</th>
                                <th scope="col">Updated at</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($import_feeds as $feed)
                                <tr class="cursor-pointer" onclick="window.location.href='{{route('admin.import-export-tool.import',  $feed->id)}}'">
                                    <th scope="row">{{$feed->id}}</th>
                                    <td>
                                        <a href="{{route('admin.import-export-tool.import',  $feed->id)}}">{{$feed->name}}</a>
                                    </td>
                                    <td>{{ucfirst($feed->import_to)}}</td>
                                    <td>{{$feed->count_of_contents}}</td>
                                    <td>
                                        @php
                                            $importStart = Carbon::createFromDate($feed->last_import_start);
                                            $importEnd = Carbon::createFromDate($feed->last_import_end);
                                            echo $importStart->diffInMinutes($importEnd);
                                        @endphp min
                                    </td>
                                    <td>{{mw()->format->human_filesize($feed->source_file_size)}}</td>
                                    <td>{{$feed->created_at}}</td>
                                    <td>{{$feed->updated_at}}</td>
                                    <td><a class="btn btn-outline-primary btn-sm" href="{{route('admin.import-export-tool.import',  $feed->id)}}">View</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    @endif

                </div>
                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">


                </div>
            </div>

        </div>
        </div>
    </div>
</div>

