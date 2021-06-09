<?php
$randomId = uniqid();
?>

<div class="card-header bg-white">
    <a href="#" data-toggle="collapse" data-target="#collapse_{{$randomId}}" aria-expanded="true" class="d-flex">
        <h6 class="title"><?php _e('Categories') ?></h6>
        <i class="mdi mdi-plus ml-auto"   ></i>
    </a>
</div>

<div class="collapse show" id="collapse_{{$randomId}}">
    <div class="card-body">

        @foreach($categories as $category)
            <ul class="js-filter-category-tree">
                <li>
                    <a href="?category={{$category->id}}" class="js-filter-category-link @if($request->get('category', false) == $category->id) active @endif ">{{$category->title}}</a>
                </li>
                @if($category->children()->count() > 0)
                    @include('blog::partials.categories_children', ['categories' => $category->children])
                @endif
            </ul>
        @endforeach

    </div>
</div>
