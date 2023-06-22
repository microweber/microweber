@if($content->media()->first())
    <div class="picture-blade-bg-img border-radius-0 border-0" style="background-image: url('{{$content->thumbnail(640,480, true)}}')">



    </div>
@else
    @include('content::admin.content.livewire.components.icon', ['content'=>$content])
@endif
