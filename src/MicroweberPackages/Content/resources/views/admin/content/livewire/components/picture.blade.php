@if($content->media()->first())
    <div class="img-as-background border-radius-0 border-0">
      {{--  <span style="font-size:6px;color:#ffffff;text-transform: uppercase;padding:2px;margin-top: 2px;position: absolute;background: rgba(0,0,0,0.32);">
            {{$content->content_type}}
        </span>--}}
        <img src="{{$content->thumbnail(640,480, true)}}" class="rounded-full">
    </div>
@else
    @include('content::admin.content.livewire.components.icon', ['content'=>$content])
@endif
