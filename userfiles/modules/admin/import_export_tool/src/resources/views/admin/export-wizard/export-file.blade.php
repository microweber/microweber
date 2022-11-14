<div class="container">
    <div class="d-flex justify-content-center">
        <div class="input-group" style="width:500px">
            <input class="form-control" value="{{route('admin.import-export-tool.export-wizard-file', $export_feed['id'])}}">
            <div class="input-group-append">
                <a href="{{route('admin.import-export-tool.export-wizard-file', $export_feed['id'])}}" class="btn btn-outline-success">
                    <i class="fa fa-download"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>
