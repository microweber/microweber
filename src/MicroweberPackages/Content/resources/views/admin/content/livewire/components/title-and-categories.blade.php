<div class="manage-item-main-top">

    <a href="{{$content->editLink()}}" class="js-open-in-modal form-label font-weight-bold text-break-line-1 text-decoration-none manage-post-item-title mb-0">
        {{$content->title}}
    </a>

    @php
    $parentPages = app()->content_manager->get_parents($content->id);
    @endphp

    @if(!empty($parentPages))
        <div class="text-muted">
        @foreach ($parentPages as $parentPageId)
            <a onclick="livewire.emit('selectPageFromTableList', {{$parentPageId}});return false;" href="#" class="my-1 d-block text-muted mw-products-breadcrumb">
                {{content_title($parentPageId)}}
            </a>
            @if(!$loop->last)
                <span class="mx-1">/</span>
            @endif
        @endforeach
        </div>
    @endif

    <div class="d-flex align-items-center gap-2">
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

        <div>
            <small class="text-muted" style="font-size: 12px !important;">
                {{ _e("Updated") }}: {{$content->updated_at->format('M d, Y, h:i A')}}
            </small>
        </div>
    </div> 

</div>
