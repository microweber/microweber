

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

            <div class="col item-title manage-post-item-col-3 manage-post-main">
                <div class="manage-item-main-top">
                    <a target="_self" href="" class="btn btn-link p-0">
                        <h5 class="text-dark text-break-line-1 mb-0 manage-post-item-title">{{$row->title}}</h5>
                    </a>
                    @if($row->categories->count() > 0)
                        <span class="manage-post-item-cats-inline-list">
                    @foreach($row->categories as $category)
                                <a href="#" class="btn btn-link p-0 text-muted">{{$category->parent->title}}</a>
                            @endforeach
                       </span>
                    @endif
                    <a class="manage-post-item-link-small mw-medium d-none d-lg-block" target="_self" href="{{$row->link()}}">
                        <small class="text-muted">{{$row->link()}}</small>
                    </a>
                </div>
                <div class="manage-post-item-links">
                    @foreach($buttons as $button)
                        <a href="{{$button['href']}}" class="{{$button['class']}}">{{$button['name']}}</a>
                    @endforeach
                </div>
            </div>

            <div class="col item-author"><span class="text-muted">{{ucfirst($row->authorName())}}</span></div>

          {{--  <div class="col item-comments" style="max-width: 100px;">
                <span class="text-muted">
                    <i class="mdi mdi-comment mdi-18px"></i>
                    <span class="float-right mx-2">0</span>
                </span>
            </div>--}}

        </div>
    </div>
</div>
