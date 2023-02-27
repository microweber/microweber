<div class="manage-item-main-top">

    <a target="_self" href="{{route('admin.page.edit', $content->id)}}" class="btn btn-link p-0">
        <h5 class="text-dark text-break-line-1 mb-0 manage-post-item-title">
            {{$content->title}}
        </h5>
    </a>

    @php
        $getParentsAsLinks = app()->content_manager->get_parents_as_links($content->id, [
            'class'=>'btn btn-link p-0 text-muted mw-products-breadcrumb',
            'implode_symbol'=>' / ',
        ])
    @endphp

    @if ($getParentsAsLinks)
        <div class="text-muted">
            {!! $getParentsAsLinks !!}
        </div>
    @endif

    @if($content->categories->count() > 0)
        <span class="manage-post-item-cats-inline-list">
            @php
                $iCategory = 0;
            @endphp
            @foreach($content->categories as $category)
                @if($category->parent)

                    <a onclick="livewire.emit('selectCategoryFromTableList', {{$category->parent->id}});return false;" href="?filters[category]={{$category->parent->id}}&showFilters[category]=1"
                       class="btn btn-link btn-sm p-0">
                        {{$category->parent->title}}
                     </a>

                    @php
                        $iCategory++;
                        if ($content->categories->count() !== $iCategory) {
                            echo ", ";
                        }
                    @endphp

                @endif
            @endforeach
             </span>
    @endif
    <a class="manage-post-item-link-small mw-medium d-none d-lg-block" target="_self"
       href="{{$content->link()}}">
        <small class="text-muted">{{$content->link()}}</small>
    </a>
</div>
