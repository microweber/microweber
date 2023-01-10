@if($content->media()->first())
    <div class="img-as-background border-radius-0 border-0">
        <img src="{{$content->thumbnail(640,480, true)}}" class="rounded-full">
    </div>
@else
    @include('content::admin.content.livewire.components.icon', ['content'=>$content])
@endif
