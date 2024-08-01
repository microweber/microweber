<a href="#">
    @if($content->media()->first())

        <div class="" style="background-image: url('{{$content->thumbnail(640,480, true)}}')"></div>
    @else
        @include('content::admin.content.filament.icon', ['content'=>$content])
    @endif
</a>
