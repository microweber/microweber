<?php
$randomId = uniqid();
?>


<h6><?php _e('Categories') ?></h6>


<div class="card-body px-1">
    @foreach($categories as $category)

        <ul class="js-filter-category-tree list-unstyled">
            <li class="mw-shop-attributes-li title d-flex align-items-center justify-content-between">
                <a href="{{$category->link()}}" data-category-id="{{$category->id}}" class="js-filter-category-link mw-admin-action-links @if($request->get('category', false) == $category->id) active @endif ">{{$category->title}}</a>
                @if(in_array($category->id, $categoriesActiveIds))
                    <button type="button" data-key="category" data-value="{{$category->id}}" class="btn btn-link js-filter-picked">
                        <i class="mdi mdi-close ml-2"></i>
                    </button>
                @endif
            </li>
            @if($category->children()->count() > 0)
                @include('blog::partials.categories_children', ['categories' => $category->children])
            @endif
        </ul>
    @endforeach

</div>

