
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
    <a href="{{route('admin.post.create')}}{{ $suffix }}" class="{{ $buttonClass }}"><?php _e('Add post'); ?></a>
@endif

@if($contentType == 'product')
    <a href="{{route('admin.product.create')}}{{ $suffix }}" class="{{ $buttonClass }}"><?php _e('Add product'); ?></a>
@endif

@if($contentType == 'page')


    <a href="{{route('admin.page.create')}}{{ $suffix }}" class="{{ $buttonClass }}"><?php _e('Add page'); ?></a>
@endif

@if($contentType == 'content')



    <div class="btn-group">
        <button type="button" class="{{ $buttonClass }} dropdown-toggle hidden-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                <?php _e('Create content'); ?>
        </button>
        <ul class="dropdown-menu">
            <a href="{{route('admin.page.create')}}{{ $suffix }}" class="dropdown-item {{ $buttonClass }}"><?php _e('Add page'); ?></a>
            <a href="{{route('admin.post.create')}}{{ $suffix }}" class="dropdown-item {{ $buttonClass }}"><?php _e('Add post'); ?></a>
            <?php if (is_shop_module_enabled_for_user()): ?>
            <a href="{{route('admin.product.create')}}{{ $suffix }}" class="dropdown-item {{ $buttonClass }}"><?php _e('Add product'); ?></a>
            <?php endif; ?>
        </ul>
    </div>

@endif
