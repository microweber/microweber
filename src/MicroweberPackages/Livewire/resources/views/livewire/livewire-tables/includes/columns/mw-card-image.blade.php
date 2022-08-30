{{--
<div class="col item-image">

    @if($row->media()->first() !== null)
        <div class="position-absolute text-muted" style="z-index: 1; right: 0; top: -10px;">
            <i class="mdi mdi-shopping mdi-18px" data-toggle="tooltip" title="" data-original-title="Продукт"></i>
        </div>
    @endif

    <div class="img-circle-holder border-radius-0 border-0">
        <a href="{{$row->link()}}">
            @if($row->media()->first() !== null)
                <img src="{{$row->thumbnail()}}">
            @else
                <i class="mdi mdi-shopping mdi-48px text-muted text-opacity-5"></i>
            @endif
        </a>
    </div>

</div>
--}}
