<div class="row timeline-event mw-ui-admin-notif-item-{{$id}}">
    <div class="col pr-0 timeline-line">
        <div class="custom-control custom-checkbox d-inline-block">
            <input type="checkbox" class="custom-control-input js-checked-checkbox" id="notif-{{$id}}" value="{{$id}}" name="checked[{{$id}}]">
            <label class="custom-control-label" for="notif-{{$id}}"></label>
        </div>

        <span class="dot btn btn-primary"></span>
    </div>
    <div class="col">
        <div class="row align-items-center mb-4" style="margin-top: -10px;">
            <div class="col item-image" style="max-width: 55px;">
                <div class="img-circle-holder w-40">
                    <i class="mdi mdi-account-check text-primary mdi-24px"></i>
                </div>
            </div>
            <div class="col-4">
                <span class="text-primary" @if(!empty($display_email)) data-toggle="tooltip" data-placement="left" data-original-title="{{$display_email}}" title="{{$display_email}}" @endif>
                  {{$display_name}}
                </span>
                <span class="text-muted">{{_e('joined the community')}}</span></div>
            <div class="col-4"><span data-toggle="tooltip" data-placement="left" data-original-title="{{$created_at}}" title="{{$created_at}}" class="text-muted"><i class="mdi mdi-clock-outline"></i> {{$ago}}</span></div>
        </div>
    </div>
</div>