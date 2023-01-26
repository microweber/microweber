

@php
    $suffix = '';
    $suffixes = [];
    if(isset($currentCategoryId) && $currentCategoryId){
        $suffixes[]= 'recommended_category_id='.$currentCategoryId;
    }
    if(isset($currentPageId) && $currentPageId){
        $suffixes[]= 'recommended_content_id='.$currentPageId;
    }

    if($suffixes){
        $suffix = '?'.implode('&', $suffixes);
    }


@endphp



@if($contentType == 'post')
    <a href="{{route('admin.post.create')}}{{ $suffix }}" class="btn btn-primary btn-rounded"><?php _e('Create a post'); ?></a>
@endif

@if($contentType == 'product')
    <a href="{{route('admin.product.create')}}{{ $suffix }}" class="btn btn-primary btn-rounded"><?php _e('Create a product'); ?></a>
@endif

@if($contentType == 'page')


    <a href="{{route('admin.page.create')}}{{ $suffix }}" class="btn btn-primary btn-rounded"><?php _e('Create a page'); ?></a>
@endif

@if($contentType == 'content')
    <a href="{{route('admin.page.create')}}{{ $suffix }}" class="btn btn-primary btn-rounded"><?php _e('Create a page'); ?></a>
    <a href="{{route('admin.post.create')}}{{ $suffix }}" class="btn btn-primary btn-rounded"><?php _e('Create a post'); ?></a>
@endif
