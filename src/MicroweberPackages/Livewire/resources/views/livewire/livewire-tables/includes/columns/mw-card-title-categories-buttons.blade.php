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

@if(isset($buttons))
<div class="manage-post-item-links mt-3">
    @foreach($buttons as $button)
    <a href="{{$button['href']}}" class="{{$button['class']}}">{{$button['name']}}</a>
    @endforeach
</div>
@endif
