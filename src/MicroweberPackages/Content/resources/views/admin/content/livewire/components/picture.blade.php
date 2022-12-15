@if($content->media()->first())
    <div class="img-circle-holder border-radius-0 border-0">
        <img src="{{$content->thumbnail(200,200)}}" class="rounded-full">
    </div>
@else
    @include('content::admin.content.livewire.components.icon', ['content'=>$content])
@endif
