
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

    if(!isset($contentType)){
        $contentType = 'content';
    }

   if(!isset($buttonClass)){
        $buttonClass = 'btn btn-primary btn-rounded';
    }
@endphp



@if($contentType == 'post')
    <a href="{{route('admin.post.create')}}{{ $suffix }}" class="{{ $buttonClass }}"><?php _e('Create a post'); ?></a>
@endif

@if($contentType == 'product')
    <a href="{{route('admin.product.create')}}{{ $suffix }}" class="{{ $buttonClass }}"><?php _e('Create a product'); ?></a>
@endif

@if($contentType == 'page')


    <a href="{{route('admin.page.create')}}{{ $suffix }}" class="{{ $buttonClass }}"><?php _e('Create a page'); ?></a>
@endif

@if($contentType == 'content')
    <a href="{{route('admin.page.create')}}{{ $suffix }}" class="{{ $buttonClass }}"><?php _e('Create a page'); ?></a>
    <a href="{{route('admin.post.create')}}{{ $suffix }}" class="{{ $buttonClass }}"><?php _e('Create a post'); ?></a>
    <?php if (user_can_view_module(['module' => 'shop.products'])): ?>
    <a href="{{route('admin.product.create')}}{{ $suffix }}" class="{{ $buttonClass }}"><?php _e('Create a product'); ?></a>
    <?php endif; ?>
@endif
