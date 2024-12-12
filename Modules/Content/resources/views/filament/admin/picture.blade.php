<a href="#">
    @if($content->media()->first())

        <div class="h-12 w-12" style="background-image: url('{{$content->thumbnail(640,480, true)}}')"></div>
    @else
        @include('modules.content::filament.admin.icon', ['content'=>$content])
    @endif
</a>
