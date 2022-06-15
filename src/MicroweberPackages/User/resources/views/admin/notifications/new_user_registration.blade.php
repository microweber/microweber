
<div class="row align-items-center mb-4"  >
    <div class="col item-image"  >
        <div class="img-circle-holder w-40">
            <i class="mdi mdi-account-check text-primary mdi-24px"></i>
        </div>
    </div>
    <div class="col-4">
        <span class="text-primary" @if(!empty($display_email)) data-bs-toggle="tooltip" data-placement="left" data-original-title="{{$display_email}}" title="{{$display_email}}" @endif>
          {{$display_name}}
        </span>
        <span class="text-muted">{{_e('joined the community')}}</span></div>
    <div class="col-4"><span data-bs-toggle="tooltip" data-placement="left" data-original-title="{{$created_at}}" title="{{$created_at}}" class="text-muted"><i class="mdi mdi-clock-outline"></i> {{$ago}}</span></div>
</div>
