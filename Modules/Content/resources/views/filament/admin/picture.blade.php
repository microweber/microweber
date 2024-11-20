<a href="#">
    @if($content->media()->first())

        <div class="" style="background-image: url('{{$content->thumbnail(640,480, true)}}')"></div>
    @else
        @include('modules.content::filament.admin.icon', ['content'=>$content])
    @endif
</a>
