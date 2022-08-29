


<div class="card card-product-holder mb-2">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col text-center" style="max-width: 40px;">
                <div class="form-group">
                    <div class="custom-control custom-checkbox mx-1">
                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                        <label class="custom-control-label" for="customCheck1"></label>
                    </div>
                </div>
                <button type="button" class="btn btn-link text-muted px-0 js-move"><i class="mdi mdi-cursor-move"></i></button>
            </div>

            <div class="col item-image">
                <div class="position-absolute text-muted" style="z-index: 1; right: 0; top: -10px;">
                    <i class="mdi mdi-shopping mdi-18px" data-toggle="tooltip" title="" data-original-title="Продукт"></i>
                </div>
                <div class="img-circle-holder border-radius-0 border-0">
                    <img src="{{$row->thumbnail()}}">
                </div>
            </div>

            <div class="col item-title">
                <h5 class="text-dark text-break-line-1 mb-0">{{$row->title}}</h5>
                @if($row->categories->count() > 0)
                    <a href="#" class="text-muted">Category 1</a>
                    <br>
                @endif
                <small class="text-muted">{{$row->link()}}</small>
                <div class="mt-2">
                    @foreach($buttons as $button)
                    <a href="{{$button['href']}}" class="{{$button['class']}}">{{$button['name']}}</a>
                    @endforeach
                </div>
            </div>

            <div class="col item-author"><span class="text-muted">{{$row->authorName()}}</span></div>
            <div class="col item-comments" style="max-width: 100px;">
                    <span class="text-muted">
                        <i class="mdi mdi-comment mdi-18px"></i>
                        <span class="float-right mx-2">0</span>
                    </span>
            </div>
        </div>
    </div>
</div>
